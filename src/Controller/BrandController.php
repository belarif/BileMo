<?php

namespace App\Controller;

use App\Exception\BrandException;
use Exception;
use OpenApi\Annotations as OA;
use App\Entity\DTO\BrandDTO;
use App\Repository\BrandRepository;
use App\Service\BrandManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Hateoas\HateoasBuilder;

/**
 * @Route("/brands", name="api_")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("", name="create_brand", methods={"POST"})
     *
     * @OA\Info(
     *     title="bile-mo API",
     *     description="bile-mo is a web service offering a selection of mobile phones",
     *     version="1.0.0"
     *     )
     * @OA\Server(
     *     url="http://localhost:8000/bile-mo-api/v1",
     *     description="bile-mo API main server"
     * )
     *
     * @OA\Post(
     *     path="/brands",
     *     summary="Create a new brand",
     *     tags={"Brands management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name"
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
        BrandManagement $brandManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $brandDTO = $serializer->deserialize($request->getContent(), BrandDTO::class, 'json');

            $errors = $validator->validate($brandDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->hateoasResponse($brandManagement->createBrand($brandDTO));
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
     * @Route("", name="brands_list", methods={"GET"})
     *
     * @OA\Get(
     *     path="/brands",
     *     summary="Returns list of brands",
     *     tags={"Brands management"},
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BrandDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(BrandManagement $brandManagement): JsonResponse
    {
        return $this->hateoasResponse($brandManagement->brandsList());
    }

    /**
     * @Route("/{id}", name="show_brand", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/brands/{id}",
     *     summary="Returns brand by id",
     *     tags={"Brands management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="brand ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BrandDTO"),
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
    public function show(int $id, BrandRepository $brandRepository): JsonResponse
    {
        try {
            return $this->hateoasResponse($brandRepository->getBrand($id));
        } catch (BrandException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }

    }

    /**
     * @Route("/{id}", name="update_brand", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\Put(
     *     path="/brands/{id}",
     *     summary="Updates a brand by id",
     *     tags={"Brands management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="brand ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BrandDTO"),
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
        BrandManagement $brandManagement,
        BrandRepository $brandRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $brandDTO = $serializer->deserialize($request->getContent(), BrandDTO::class, 'json');

            $errors = $validator->validate($brandDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->hateoasResponse($brandManagement->updateBrand($brandRepository->getBrand($id), $brandDTO));
        } catch (BrandException $e) {
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
     * @Route("/{id}", name="delete_brand", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @OA\Delete(
     *     path="/brands/{id}",
     *     summary="Deletes a brand by id",
     *     tags={"Brands management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="brand ID",
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
    public function delete(int $id, BrandManagement $brandManagement, BrandRepository $brandRepository): JsonResponse
    {
        try {
            $brandManagement->deleteBrand($brandRepository->getBrand($id));
            return $this->json('La marque a été supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (BrandException $e) {
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

