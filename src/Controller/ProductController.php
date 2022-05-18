<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Exception;
use App\Entity\DTO\ProductDTO;
use App\Service\ProductManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($productManagement->createProduct($productDTO), Response::HTTP_CREATED, [], ['groups' => 'show_product']);

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
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
     */
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        try {
            return $this->json($productRepository->getProduct($id), Response::HTTP_OK, [], ['groups' => 'show_product']);

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("/{id}", name="update_product", methods={"PUT"})
     */
    public function update(
        int $id,
        Request $request,
        SerializerInterface $serializer,
        ProductRepository $productRepository,
        ProductManagement $productManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $productDTO = $serializer->deserialize($request->getContent(), ProductDTO::class, 'json');

            $errors = $validator->validate($productDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($productManagement->updateProduct($productRepository->getProduct($id),$productDTO), Response::HTTP_CREATED, [], ['groups' => 'show_product']);

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("/{id}", name="delete_product", methods={"DELETE"})
     */
    public function delete(int $id, ProductRepository $productRepository, ProductManagement $productManagement): JsonResponse
    {
        try {
            $productManagement->deleteProduct($productRepository->getProduct($id));

            return $this->json('Le produit est supprimé avec succès', Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }
}
