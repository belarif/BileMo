<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\productManagement;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="api_create_product", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param productManagement $productManagement
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, productManagement $productManagement):JsonResponse
    {
        /**
         * @var ProductDTO $productDTO
         */
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');
        $productManagement->createProduct($productDTO);

        return new JsonResponse('le produit a été créé avec succès','201');
    }
}

