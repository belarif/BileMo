<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductManagement;

/**
 * @Route("/products", name="api_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="create_product", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ProductManagement $productManagement
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, ProductManagement $productManagement): JsonResponse
    {
        /**
         * @var ProductDTO $productDTO
         */
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');
        $productManagement->createProduct($productDTO);

        return new JsonResponse('le produit a été créé avec succès','201');
    }

    /**
     * @Route("/{product_id}", name="delete_product", methods={"DELETE"})
     * @param Request $request
     * @param ProductManagement $productManagement
     * @return JsonResponse
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(Request $request, ProductManagement $productManagement): JsonResponse
    {
        $productManagement->deleteProduct($request->get('product_id'));

        return new JsonResponse('Le produit est supprimé avec succès');
    }
}

