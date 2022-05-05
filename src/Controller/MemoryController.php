<?php

namespace App\Controller;

use App\Entity\DTO\MemoryDTO;
use App\Entity\Memory;
use App\Service\MemoryManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/memories", name="api_")
 */
class MemoryController extends AbstractController
{
    /**
     * @Route("", name="create_memory", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, MemoryManagement $memoryManagement, ValidatorInterface $validator): JsonResponse
    {
        $memoryDTO = $serializer->deserialize($request->getContent(),MemoryDTO::class,'json');

        $errors = $validator->validate($memoryDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $memoryManagement->createMemory($memoryDTO);

        return $this->json('La memoire a été ajouté avec succès ',Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="memories_list", methods={"GET"})
     */
    public function list(MemoryManagement $memoryManagement): JsonResponse
    {
        return $this->json($memoryManagement->memoriesList(),Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_memory", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @Entity("memory", expr="repository.getMemory(id)")
     */
    public function show(Memory $memory): JsonResponse
    {
        return $this->json($memory,Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_memory", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @Entity("memory", expr="repository.getMemory(id)")
     */
    public function update(Request $request, Memory $memory, MemoryManagement $memoryManagement, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $memoryDTO = $serializer->deserialize($request->getContent(),MemoryDTO::class,'json');

        $errors = $validator->validate($memoryDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $memoryManagement->updateMemory($memory,$memoryDTO);

        return $this->json('La memoire a été mise à jour avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_memory", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("memory", expr="repository.getMemory(id)")
     */
    public function delete(Memory $memory, MemoryManagement $memoryManagement): JsonResponse
    {
        $memoryManagement->deleteMemory($memory);

        return $this->json('La memoire a été supprimé avec succès',Response::HTTP_OK);
    }
}


