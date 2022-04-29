<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
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
     */
    public function create(Request $request, SerializerInterface $serializer, ColorManagement $colorManagement): JsonResponse
    {
        $colorDTO = $serializer->deserialize($request->getContent(),ColorDTO::class,'json');

        $colorManagement->createColor($colorDTO);

        return $this->json('La couleur a été ajouté avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="colors_list", methods={"GET"})
     */
    public function list(ColorManagement $colorManagement): JsonResponse
    {
        return $this->json($colorManagement->colorsList(),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_color", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Color $color): JsonResponse
    {
        return $this->json($color,200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_color", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request, Color $color, ColorManagement $colorManagement, SerializerInterface $serializer): JsonResponse
    {
        $colorDTO = $serializer->deserialize($request->getContent(),ColorDTO::class,'json');
        $colorManagement->updateColor($color,$colorDTO);

        return $this->json('La couleur a été modifié avec succès',200,['Content-Type' => 'text/plain']);
    }
}
