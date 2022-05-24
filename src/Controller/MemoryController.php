<?php

namespace App\Controller;

use App\Exception\MemoryException;
use Exception;
use OpenApi\Annotations as OA;
use App\Repository\MemoryRepository;
use App\Entity\DTO\MemoryDTO;
use App\Service\MemoryManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/memories", name="api_")
 */
class MemoryController extends AbstractController
{
    /**
     * @Route("", name="create_memory", methods={"POST"})
     *
     * @OA\Post(
     *     path="/memories",
     *     summary="Create a new memory",
     *     tags="D",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="memoryCapacity"
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
        MemoryManagement $memoryManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $memoryDTO = $serializer->deserialize($request->getContent(), MemoryDTO::class, 'json');

            $errors = $validator->validate($memoryDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($memoryManagement->createMemory($memoryDTO), Response::HTTP_CREATED);

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
     * @Route("", name="memories_list", methods={"GET"})
     *
     * @OA\Get(
     *     path="/memories",
     *     summary="Returns list of memories",
     *     tags="D",
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MemoryDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(MemoryManagement $memoryManagement): JsonResponse
    {
        return $this->json($memoryManagement->memoriesList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_memory", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/memories/{id}",
     *     summary="Returns memory by id",
     *     tags="D",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="memory ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MemoryDTO"),
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
    public function show(int $id, MemoryRepository $memoryRepository): JsonResponse
    {
        try {
            return $this->json($memoryRepository->getMemory($id), Response::HTTP_OK);

        } catch (MemoryException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );

        }
    }

    /**
     * @Route("/{id}", name="update_memory", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\Put(
     *     path="/memories/{id}",
     *     summary="Updates a memory by id",
     *     tags="D",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="memory ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="memoryCapacity"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MemoryDTO"),
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
        MemoryRepository $memoryRepository,
        MemoryManagement $memoryManagement,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $memoryDTO = $serializer->deserialize($request->getContent(), MemoryDTO::class, 'json');

            $errors = $validator->validate($memoryDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($memoryManagement->updateMemory($memoryRepository->getMemory($id), $memoryDTO), Response::HTTP_CREATED);

        } catch (MemoryException $e) {
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
     * @Route("/{id}", name="delete_memory", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @OA\Delete(
     *     path="/memories/{id}",
     *     summary="Deletes a memory by id",
     *     tags="D",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="memory ID",
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
    public function delete(int $id, MemoryRepository $memoryRepository, MemoryManagement $memoryManagement): JsonResponse
    {
        try {
            $memoryManagement->deleteMemory($memoryRepository->getMemory($id));

            return $this->json('La memoire a été supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (MemoryException $e) {
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
