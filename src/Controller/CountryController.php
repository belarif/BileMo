<?php

namespace App\Controller;

use Exception;
use OpenApi\Annotations as OA;
use App\Entity\DTO\CountryDTO;
use App\Exception\CountryException;
use App\Repository\CountryRepository;
use App\Service\CountryManagement;
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
     *
     * @OA\Post(
     *     path="/countries",
     *     summary="Create a new country",
     *     tags={"Countries management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     )
     * )
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
     *
     * @OA\Get(
     *     path="/countries",
     *     summary="Returns list of countries",
     *     tags={"Countries management"},
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CountryDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(CountryManagement $countryManagement): JsonResponse
    {
        return $this->json($countryManagement->countriesList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_country", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/countries/{id}",
     *     summary="Returns country by id",
     *     tags={"Countries management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="country ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CountryDTO"),
     *             description="Ok"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function show(int $id, CountryRepository $countryRepository): JsonResponse
    {
        try {
            return $this->json($countryRepository->getCountry($id), Response::HTTP_OK);

        } catch (CountryException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @Route("/{id}", name="update_country", methods={"PUT"}, requirements={"id"="\d+"})
     *
     * @OA\Put(
     *     path="/countries/{id}",
     *     summary="Updates a country by id",
     *     tags={"Countries management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="country ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CountryDTO"),
     *             description="Created"
     *         )
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="HTTP_CONFLICT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Conflict"
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="HTTP_BAD_REQUEST",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Bad request"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
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

        } catch (CountryException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
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
     *
     * @OA\Delete(
     *     path="/countries/{id}",
     *     summary="Deletes a country by id",
     *     tags={"Countries management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="country ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="HTTP_NO_CONTENT",
     *         @OA\JsonContent(
     *             type="string",
     *             description="No content"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="HTTP_NOT_FOUND",
     *         @OA\JsonContent(
     *             type="string",
     *             description="Not found"
     *         )
     *     )
     * )
     */
    public function delete(int $id, CountryRepository $countryRepository, CountryManagement $countryManagement): JsonResponse
    {
        try {
            $countryManagement->deleteCountry($countryRepository->getCountry($id));

            return $this->json('La pays a été supprimé avec succès', Response::HTTP_NO_CONTENT);

        } catch (CountryException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
