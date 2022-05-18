<?php

namespace App\Controller;

use Exception;
use App\Entity\DTO\BrandDTO;
use App\Repository\BrandRepository;
use App\Service\BrandManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/brands", "api_")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("", name="create_brand", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        BrandManagement $brandManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $brandDTO = $serializer->deserialize($request->getContent(), BrandDTO::class, 'json');

            $errors = $validator->validate($brandDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($brandManagement->createBrand($brandDTO), Response::HTTP_CREATED);
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
     * @Route("", name="brands_list", methods={"GET"})
     */
    public function list(BrandManagement $brandManagement): JsonResponse
    {
        return $this->json($brandManagement->brandsList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_brand", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(int $id, BrandRepository $brandRepository): JsonResponse
    {
        try {
            return $this->json($brandRepository->getBrand($id), Response::HTTP_OK);

        } catch(Exception $e) {
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
     * @Route("/{id}", name="update_brand", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function update(
        int $id,
        Request $request,
        BrandManagement $brandManagement,
        BrandRepository $brandRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $brandDTO = $serializer->deserialize($request->getContent(), BrandDTO::class, 'json');

            $errors = $validator->validate($brandDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($brandManagement->updateBrand($brandRepository->getBrand($id), $brandDTO), Response::HTTP_CREATED);

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
     * @Route("/{id}", name="delete_brand", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id, BrandManagement $brandManagement, BrandRepository $brandRepository): JsonResponse
    {
        try {
            $brandManagement->deleteBrand($brandRepository->getBrand($id));
            return $this->json('La marque a été supprimé avec succès', Response::HTTP_OK);

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
