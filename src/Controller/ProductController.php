<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/products", name="api_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="create_product", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ProductManagement $productManagement, ValidatorInterface $validator): JsonResponse
    {
        /**
         * @var ProductDTO $productDTO
         */
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

        $errors = $validator->validate($productDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $productManagement->createProduct($productDTO);

        return $this->json('le produit a été créé avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="products_list", methods={"GET"})
     */
    public function list(ProductManagement $productManagement): JsonResponse
    {
        return $this->json($productManagement->productsList(),Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="show_product", methods={"GET"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function show(Product $product): JsonResponse
    {
        return $this->json($product,Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_product", methods={"PUT"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function update(Request $request, SerializerInterface $serializer, ProductManagement $productManagement, Product $product, ValidatorInterface $validator): JsonResponse
    {
        $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

        $errors = $validator->validate($productDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $productManagement->updateProduct($productDTO,$product);

        return $this->json('Le produit est mise à jour avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function delete(Product $product, ProductManagement $productManagement): JsonResponse
    {
        $productManagement->deleteProduct($product);

        return $this->json('Le produit est supprimé avec succès',Response::HTTP_OK);
    }
}




