<?php

namespace App\Controller;

use Exception;
use App\Entity\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use App\Service\CustomerManagement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json($customerManagement->createCustomer($customerDTO), Response::HTTP_CREATED);

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
     * @Route("", name="customers_list", methods={"GET"})
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->json($customerManagement->customersList(), Response::HTTP_OK, [], ['groups' => ['show_customer']]);
    }

    /**
     * @Route("/{customer_id}", name="show_customer", methods={"GET"})
     */
    public function show(int $customer_id, CustomerRepository $customerRepository): JsonResponse
    {
        try {
            return $this->json($customerRepository->getCustomer($customer_id), Response::HTTP_OK, [], ['groups' => ['show_customer']]);

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
     * @Route("/{customer_id}", name="update_customer", methods={"PUT"})
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
                        'message' => $errors[0]->getMessage()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->json(
                $customerManagement->updateCustomer($customerRepository->getCustomer($customer_id) ,$customerDTO),
                Response::HTTP_CREATED,
                [],
                ['groups' => ['show_customer']]
            );

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
     * @Route("/{customer_id}", name="delete_customer", methods={"DELETE"})
     */
    public function delete(int $customer_id, CustomerRepository $customerRepository, CustomerManagement $customerManagement): JsonResponse
    {
        try {
            $customerManagement->deletecCustomer($customerRepository->getCustomer($customer_id));

            return $this->json('Le client est supprimé avec succès', Response::HTTP_OK);

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
