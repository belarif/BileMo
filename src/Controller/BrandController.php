<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\DTO\BrandDTO;
use App\Service\BrandManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
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
                return $this->json(['success' => false, 'message' => $errors[0]->getMessage()], Response::HTTP_CONFLICT);
            }

            return $this->json($brandManagement->createBrand($brandDTO), Response::HTTP_CREATED);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
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
     *
     * @Entity("brand", expr="repository.getBrand(id)")
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->json($brand, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_brand", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @Entity("brand", expr="repository.getBrand(id)")
     */
    public function update(
        Request $request,
        Brand $brand,
        BrandManagement $brandManagement,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $brandDTO = $serializer->deserialize($request->getContent(), BrandDTO::class, 'json');

            $errors = $validator->validate($brandDTO);

            if ($errors->count()) {
                return $this->json(['success' => false, 'message' => $errors[0]->getMessage()], Response::HTTP_CONFLICT);
            }

            return $this->json($brandManagement->updateBrand($brand, $brandDTO), Response::HTTP_CREATED);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{id}", name="delete_brand", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @Entity("brand", expr="repository.getBrand(id)")
     */
    public function delete(Brand $brand, BrandManagement $brandManagement): JsonResponse
    {
        $brandManagement->deleteBrand($brand);

        return $this->json('La marque a été supprimé avec succès', Response::HTTP_OK);
    }
}
