<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
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
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
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
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     * @param Product $product
     * @param ProductManagement $productManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Product $product, ProductManagement $productManagement): JsonResponse
    {
        $productManagement->deleteProduct($product);

        return new JsonResponse('Le produit est supprimé avec succès');
    }

    /**
     * @Route("", name="products_list", methods={"GET"})
     * @param ProductManagement $productManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function list(ProductManagement $productManagement): JsonResponse
    {
        return $this->json($productManagement->productsList(),'200',['Content-Type' => 'application/json']);
    }
}


