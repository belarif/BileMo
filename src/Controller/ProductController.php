<?php

namespace App\Controller;

use App\Exception\ProductException;
use App\Repository\ProductRepository;
use Exception;
use Hateoas\HateoasBuilder;
use OpenApi\Annotations as OA;
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
     *
     * @OA\Post(
     *     path="/products",
     *     summary="Create a new product",
     *     tags={"Products management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="colors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="country",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                  ),
     *             ),
     *             @OA\Property(
     *                 property="brand",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             ),
     *             @OA\Property(
     *                 property="memory",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     )
     * )
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

            return $this->hateoasResponse($productManagement->createProduct($productDTO));
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
     *
     * @OA\Get(
     *     path="/products",
     *     summary="Returns list of products",
     *     tags={"Products management"},
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(ProductManagement $productManagement): JsonResponse
    {
        return $this->hateoasResponse($productManagement->productsList());
    }

    /**
     * @Route("/{id}", name="show_product", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Returns product by id",
     *     tags={"Products management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductDTO"),
     *             description="Ok"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        try {
            return $this->hateoasResponse($productRepository->getProduct($id));
        } catch (ProductException $e) {
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
     * @Route("/{id}", name="update_product", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Updates a product by id",
     *     tags={"Products management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="colors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="country",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                  ),
     *             ),
     *             @OA\Property(
     *                 property="brand",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             ),
     *             @OA\Property(
     *                 property="memory",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                      ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductDTO"),
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
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

            return $this->hateoasResponse($productManagement->updateProduct($productRepository->getProduct($id),$productDTO));
        } catch (ProductException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
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
     * @Route("/{id}", name="delete_product", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Deletes a product by id",
     *     tags={"Products management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="HTTP_NO_CONTENT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="No content"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function delete(int $id, ProductRepository $productRepository, ProductManagement $productManagement): JsonResponse
    {
        try {
            $productManagement->deleteProduct($productRepository->getProduct($id));

            return $this->json('Le produit est supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (ProductException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    private function hateoasResponse($data): JsonResponse {
        $hateoas = HateoasBuilder::create()->build();

        return new JsonResponse($hateoas->serialize($data, 'json'), Response::HTTP_OK, [], 'json');
    }
}

