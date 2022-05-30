<?php

namespace App\Controller;

use App\Entity\DTO\CustomerDTO;
use App\Exception\CustomerException;
use App\Repository\CustomerRepository;
use App\Service\CustomerManagement;
use Exception;
use Hateoas\HateoasBuilder;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers", name="api_", requirements={"customer_id"="\d+"})
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("", name="create_customer", methods={"POST"})
     *
     * @OA\Post(
     *     path="/customers",
     *     summary="Create a new customer",
     *     tags={"Customers management"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="company"
     *             ),
     *             @OA\Property(
     *                 property="enabled",
     *                 type="boolean"
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
        CustomerManagement $customerManagement,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');

            $errors = $validator->validate($customerDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->hateoasResponse($customerManagement->createCustomer($customerDTO));
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
     * @Route("", name="customers_list", methods={"GET"})
     *
     * @OA\Get(
     *     path="/customers",
     *     summary="Returns list of customers",
     *     tags={"Customers management"},
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerDTO"),
     *             description="Ok"
     *         )
     *     )
     * )
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->hateoasResponse($customerManagement->customersList());
    }

    /**
     * @Route("/{customer_id}", name="show_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @OA\Get(
     *     path="/customers/{customer_id}",
     *     summary="Returns customer by id",
     *     tags={"Customers management"},
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="path",
     *         description="customer ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="HTTP_OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerDTO"),
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
    public function show(int $customer_id, CustomerRepository $customerRepository): JsonResponse
    {
        try {
            return $this->hateoasResponse($customerRepository->getCustomer($customer_id));
        } catch (CustomerException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @Route("/{customer_id}", name="update_customer", methods={"PUT"}, requirements={"customer_id"="\d+"})
     *
     * @OA\Put(
     *     path="/customers/{customer_id}",
     *     summary="Updates a customer by id",
     *     tags={"Customers management"},
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="path",
     *         description="customer ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="company"
     *             ),
     *             @OA\Property(
     *                 property="enabled",
     *                 type="boolean"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="HTTP_CREATED",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerDTO"),
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
        int $customer_id,
        Request $request,
        SerializerInterface $serializer,
        CustomerManagement $customerManagement,
        CustomerRepository $customerRepository,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');

            $errors = $validator->validate($customerDTO);
            if ($errors->count()) {
                return $this->json(
                    [
                        'success' => false,
                        'message' => $errors[0]->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->hateoasResponse($customerManagement->updateCustomer($customerRepository->getCustomer($customer_id), $customerDTO));
        } catch (CustomerException $e) {
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
     * @Route("/{customer_id}", name="delete_customer", methods={"DELETE"}, requirements={"customer_id"="\d+"})
     *
     * @OA\Delete(
     *     path="/customers/{customer_id}",
     *     summary="Deletes a customer by id",
     *     tags={"Customers management"},
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="path",
     *         description="customer ID",
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
    public function delete(int $customer_id, CustomerRepository $customerRepository, CustomerManagement $customerManagement): JsonResponse
    {
        try {
            $customerManagement->deletecCustomer($customerRepository->getCustomer($customer_id));

            return $this->json('Le client est supprimé avec succès', Response::HTTP_NO_CONTENT);
        } catch (CustomerException $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    private function hateoasResponse($data): JsonResponse
    {
        $hateoas = HateoasBuilder::create()->build();

        return new JsonResponse($hateoas->serialize($data, 'json'), Response::HTTP_OK, [], 'json');
    }
}
