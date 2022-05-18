<?php

namespace App\Controller;

use App\Entity\DTO\CountryDTO;
use App\Repository\CountryRepository;
use App\Service\CountryManagement;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/countries", name="api_")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("", name="create_country", methods={"POST"})
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        CountryManagement $countryManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $countryDTO = $serializer->deserialize($request->getContent(), CountryDTO::class, 'json');

            $errors = $validator->validate($countryDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($countryManagement->createCountry($countryDTO), Response::HTTP_CREATED);

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
     * @Route("", name="countries_list", methods={"GET"})
     */
    public function list(CountryManagement $countryManagement): JsonResponse
    {
        return $this->json($countryManagement->countriesList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_country", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(int $id, CountryRepository $countryRepository): JsonResponse
    {
        try {
            return $this->json($countryRepository->getCountry($id), Response::HTTP_OK);
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
     * @Route("/{id}", name="update_country", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function update(
        int $id,
        Request $request,
        CountryRepository $countryRepository,
        CountryManagement $countryManagement,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $countryDTO = $serializer->deserialize($request->getContent(), CountryDTO::class, 'json');

            $errors = $validator->validate($countryDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($countryManagement->updateCountry($countryRepository->getCountry($id), $countryDTO), Response::HTTP_CREATED);

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
     * @Route("/{id}", name="delete_country", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(int $id, CountryRepository $countryRepository, CountryManagement $countryManagement): JsonResponse
    {
        try {
            $countryManagement->deleteCountry($countryRepository->getCountry($id));

            return $this->json('La pays a été supprimé avec succès', Response::HTTP_OK);

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
