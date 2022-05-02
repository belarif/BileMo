<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/products", name="api_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="create_product", methods={"POST"})
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
     * @Route("", name="products_list", methods={"GET"})
     */
    public function list(ProductManagement $productManagement): JsonResponse
    {
        return $this->json($productManagement->productsList(),'200',['Content-Type' => 'application/json']);
    }


    /**
     * @Route("/{id}", name="show_product", methods={"GET"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function show(Product $product): JsonResponse
    {
        return $this->json($product,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_product", methods={"PUT"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function update(Request $request, SerializerInterface $serializer, ProductManagement $productManagement, Product $product): JsonResponse
    {
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

        $productManagement->updateProduct($productDTO,$product);

        return new JsonResponse('Le produit est mise à jour avec succès');

    }

    /**
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function delete(Product $product, ProductManagement $productManagement): JsonResponse
    {
        $productManagement->deleteProduct($product);

        return new JsonResponse('Le produit est supprimé avec succès');
    }
}



