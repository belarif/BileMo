<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
use App\Service\ColorManagement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/colors", name="api_")
 */
class ColorController extends AbstractController
{
    /**
     * @Route("", name="create_color", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ColorManagement $colorManagement, ValidatorInterface $validator): JsonResponse
    {
        $colorDTO = $serializer->deserialize($request->getContent(),ColorDTO::class,'json');

        $errors = $validator->validate($colorDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $colorManagement->createColor($colorDTO);

        return $this->json('La couleur a été ajouté avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="colors_list", methods={"GET"})
     */
    public function list(ColorManagement $colorManagement): JsonResponse
    {
        return $this->json($colorManagement->colorsList(),Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_color", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @Entity("color", expr="repository.getColor(id)")
     */
    public function show(Color $color): JsonResponse
    {
        return $this->json($color,Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_color", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @Entity("color", expr="repository.getColor(id)")
     */
    public function update(Request $request, Color $color, ColorManagement $colorManagement, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $colorDTO = $serializer->deserialize($request->getContent(),ColorDTO::class,'json');

        $errors = $validator->validate($colorDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $colorManagement->updateColor($color,$colorDTO);

        return $this->json('La couleur a été modifié avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_color", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("color", expr="repository.getColor(id)")
     */
    public function delete(Color $color, ColorManagement $colorManagement): JsonResponse
    {
        $colorManagement->deleteColor($color);

        return $this->json('La couleur a été supprimé avec succès',Response::HTTP_OK);
    }
}


