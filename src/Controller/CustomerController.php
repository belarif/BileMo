<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Service\CustomerManagement;
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
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json($customerManagement->createCustomer($customerDTO), Response::HTTP_CREATED);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("", name="customers_list", methods={"GET"})
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->json($customerManagement->customersList(), Response::HTTP_OK);
    }

    /**
     * @Route("/{customer_id}", name="show_customer", methods={"GET"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->json($customer, Response::HTTP_OK);
    }

    /**
     * @Route("/{customer_id}", name="update_customer", methods={"PUT"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function update(
        Request $request,
        SerializerInterface $serializer,
        CustomerManagement $customerManagement,
        Customer $customer,
        ValidatorInterface $validator
    ): JsonResponse {
        try {
            $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');

            $errors = $validator->validate($customerDTO);

            if ($errors->count()) {
                return $this->json($errors[0]->getMessage(), Response::HTTP_CONFLICT);
            }

            return $this->json($customerManagement->updateCustomer($customerDTO, $customer), Response::HTTP_CREATED);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(), ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("/{customer_id}", name="delete_customer", methods={"DELETE"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function delete(Customer $customer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerManagement->deletecCustomer($customer);

        return $this->json('Le client est supprimé avec succès', Response::HTTP_OK);
    }
}
