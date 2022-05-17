<?php

namespace App\Controller;

use Exception;
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
                        'status' => Response::HTTP_CONFLICT,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_CONFLICT
                );
            }

            return $this->json($memoryManagement->createMemory($memoryDTO), Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->json(
                [
                'status' => Response::HTTP_CONFLICT,
                'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("", name="memories_list", methods={"GET"})
     */
    public function list(MemoryManagement $memoryManagement): JsonResponse
    {
        return $this->json($memoryManagement->memoriesList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_memory", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(int $id, MemoryRepository $memoryRepository): JsonResponse
    {
        try {
            return $this->json($memoryRepository->getMemory($id), Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => Response::HTTP_CONFLICT,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_CONFLICT
            );

        }
    }

    /**
     * @Route("/{id}", name="update_memory", methods={"PUT"}, requirements={"id"="\d+"})
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
                        'status' => Response::HTTP_CONFLICT,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_CONFLICT
                );
            }

            return $this->json($memoryManagement->updateMemory($memoryRepository->getMemory($id), $memoryDTO), Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->json(
                [
                'status' => Response::HTTP_CONFLICT,
                'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_memory", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id, MemoryRepository $memoryRepository, MemoryManagement $memoryManagement): JsonResponse
    {
        try {
            $memoryManagement->deleteMemory($memoryRepository->getMemory($id));

            return $this->json('La memoire a été supprimé avec succès', Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->json(
                [
                    'status' => Response::HTTP_CONFLICT,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_CONFLICT
            );
        }

    }
}
