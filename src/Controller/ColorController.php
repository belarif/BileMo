<?php

namespace App\Controller;

use Exception;
use App\Entity\DTO\ColorDTO;
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

/**
 * @Route("/colors", name="api_")
 */
class ColorController extends AbstractController
{
    /**
     * @Route("", name="create_color", methods={"POST"})
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

            return $this->json($colorManagement->createColor($colorDTO), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("", name="colors_list", methods={"GET"})
     */
    public function list(ColorManagement $colorManagement): JsonResponse
    {
        return $this->json($colorManagement->colorsList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_color", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(int $id, ColorRepository $colorRepository): JsonResponse
    {
        try {
            return $this->json($colorRepository->getColor($id), Response::HTTP_OK);
        }
        catch (Exception $e) {
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
     * @Route("/{id}", name="update_color", methods={"PUT"}, requirements={"id"="\d+"})
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

            return $this->json($colorManagement->updateColor($colorRepository->getColor($id), $colorDTO), Response::HTTP_CREATED);
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
     */
    public function delete(int $id, ColorRepository $colorRepository, ColorManagement $colorManagement): JsonResponse
    {
        try {
            $colorManagement->deleteColor($colorRepository->getColor($id));

            return $this->json('La couleur a été supprimé avec succès', Response::HTTP_OK);

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
}
