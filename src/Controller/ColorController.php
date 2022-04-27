<?php

namespace App\Controller;

use App\Entity\DTO\ColorDTO;
use App\Repository\ColorRepository;
use App\Service\ColorManagement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/colors", name="api_")
 */
class ColorController extends AbstractController
{
    /**
     * @Route("", name="create_color", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ColorManagement $colorManagement
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, ColorManagement $colorManagement): JsonResponse
    {
        $colorDTO = $serializer->deserialize($request->getContent(),ColorDTO::class,'json');

        $colorManagement->createColor($colorDTO);

        return $this->json('La couleur a été ajouté avec succès',200,['Content-Type' => 'text/plain']);
    }
}
