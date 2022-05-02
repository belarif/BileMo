<?php

namespace App\Controller;

use App\Entity\DTO\MemoryDTO;
use App\Entity\Memory;
use App\Service\MemoryManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/memories", name="api_")
 */
class MemoryController extends AbstractController
{
    /**
     * @Route("", name="create_memory", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, MemoryManagement $memoryManagement): JsonResponse
    {
        $memoryDTO = $serializer->deserialize($request->getContent(),MemoryDTO::class,'json');

        $memoryManagement->createMemory($memoryDTO);

        return $this->json('La memoire a été ajouté avec succès ',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="memories_list", methods={"GET"})
     */
    public function list(MemoryManagement $memoryManagement): JsonResponse
    {
        return $this->json($memoryManagement->memoriesList(),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_memory", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @Entity("memory", expr="repository.getMemory(id)")
     */
    public function show(Memory $memory): JsonResponse
    {
        return $this->json($memory,200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_memory", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @Entity("memory", expr="repository.getMemory(id)")
     */
    public function update(Request $request, Memory $memory, MemoryManagement $memoryManagement, SerializerInterface $serializer): JsonResponse
    {
        $memoryDTO = $serializer->deserialize($request->getContent(),MemoryDTO::class,'json');

        $memoryManagement->updateMemory($memory,$memoryDTO);

        return $this->json('La memoire a été mise à jour avec succès',200,["Content-Type" => "text/plain"]);
    }
}
