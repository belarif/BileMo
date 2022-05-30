<?php

namespace App\Controller;

use App\Exception\ColorException;
use Exception;
use App\Entity\DTO\ColorDTO;
use OpenApi\Annotations as OA;
use App\Repository\ColorRepository;
use App\Service\ColorManagement;
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
 * @Route("/colors", name="api_")
 */
class ColorController extends AbstractController
{
    /**
     * @Route("", name="create_color", methods={"POST"})
     *
     * @OA\Post(
     *     path="/colors",
     *     summary="Create a new color",
     *     tags={"Colors management"},
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
        ColorManagement $colorManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $colorDTO = $serializer->deserialize($request->getContent(), ColorDTO::class, 'json');

            $errors = $validator->validate($colorDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $hateoas = HateoasBuilder::create()->build();

            return new JsonResponse($hateoas->serialize($colorManagement->createColor($colorDTO), 'json'),Response::HTTP_OK,[],'json');
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("", name="colors_list", methods={"GET"})
     *
     * @OA\Get(
     *     path="/colors",
     *     summary="Returns list of colors",
     *     tags={"Colors management"},
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ColorDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(ColorManagement $colorManagement): JsonResponse
    {
        $hateoas = HateoasBuilder::create()->build();

        return new JsonResponse($hateoas->serialize($colorManagement->colorsList(), 'json'),Response::HTTP_OK,[],'json');
    }

    /**
     * @Route("/{id}", name="show_color", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/colors/{id}",
     *     summary="Returns color by id",
     *     tags={"Colors management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="color ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ColorDTO"),
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
    public function show(int $id, ColorRepository $colorRepository): JsonResponse
    {
        try {
            $hateoas = HateoasBuilder::create()->build();

            return new JsonResponse($hateoas->serialize($colorRepository->getColor($id), 'json'),Response::HTTP_OK,[],'json');
        }
        catch (ColorException $e) {
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
     * @Route("/{id}", name="update_color", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\Put(
     *     path="/colors/{id}",
     *     summary="Updates a color by id",
     *     tags={"Colors management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="color ID",
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
     *             @OA\Items(ref="#/components/schemas/ColorDTO"),
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
        ColorManagement $colorManagement,
        ColorRepository $colorRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $colorDTO = $serializer->deserialize($request->getContent(), ColorDTO::class, 'json');

            $errors = $validator->validate($colorDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $color = $colorManagement->updateColor($colorRepository->getColor($id), $colorDTO);
            $hateoas = HateoasBuilder::create()->build();

            return new JsonResponse($hateoas->serialize($color, 'json'),Response::HTTP_OK,[],'json');
        } catch (ColorException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_color", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @OA\Delete(
     *     path="/colors/{id}",
     *     summary="Deletes a color by id",
     *     tags={"Colors management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="color ID",
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
    public function delete(int $id, ColorRepository $colorRepository, ColorManagement $colorManagement): JsonResponse
    {
        try {
            $colorManagement->deleteColor($colorRepository->getColor($id));

            return $this->json('La couleur a été supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (ColorException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
