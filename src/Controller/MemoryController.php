<?php

namespace App\Controller;

use App\Entity\DTO\MemoryDTO;
use App\Service\MemoryManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
}
