<?php

namespace App\Controller;

use App\Entity\DTO\ProductDTO;
use App\Entity\Product;
use App\Service\ProductManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/products", name="api_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="create_product", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ProductManagement $productManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            /**
             * @var ProductDTO $productDTO
             */
            $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

            $errors = $validator->validate($productDTO);

            if ($errors->count()) {
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json($productManagement->createProduct($productDTO), Response::HTTP_CREATED, [], ['groups' => 'show_product']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("", name="products_list", methods={"GET"})
     */
    public function list(ProductManagement $productManagement): JsonResponse
    {
        return $this->json($productManagement->productsList(), Response::HTTP_OK, [], ['groups' => 'show_product']);
    }

    /**
     * @Route("/{id}", name="show_product", methods={"GET"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function show(Product $product): JsonResponse
    {
        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'show_product']);
    }

    /**
     * @Route("/{id}", name="update_product", methods={"PUT"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        ProductManagement $productManagement,
        Product $product,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

            $errors = $validator->validate($productDTO);

            if ($errors->count()) {
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json($productManagement->updateProduct($productDTO, $product), Response::HTTP_CREATED, [], ['groups' => 'show_product']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     *
     * @Entity("product", expr="repository.getProduct(id)")
     */
    public function delete(Product $product, ProductManagement $productManagement): JsonResponse
    {
        $productManagement->deleteProduct($product);

        return $this->json('Le produit est supprimé avec succès', Response::HTTP_OK);
    }
}
