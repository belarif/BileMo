<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Service\CustomerManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers", name="api_")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("", name="create_customer", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement, ValidatorInterface $validator): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(),CustomerDTO::class,'json');

        $errors = $validator->validate($customerDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }

        $customerManagement->createCustomer($customerDTO);

        return $this->json("Le client a été créé avec succès",Response::HTTP_CREATED);
    }

    /**
     * @Route("", name="customers_list", methods={"GET"})
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->json($customerManagement->customersList(),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_customer", methods={"GET"})
     *
     * @Entity("customer", expr="repository.getCustomer(id)")
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->json($customer,200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     *
     * @Entity("customer", expr="repository.getCustomer(id)")
     */
    public function update(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement, Customer $customer, ValidatorInterface $validator): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');

        $errors = $validator->validate($customerDTO);

        if($errors->count()) {
            return $this->json($errors[0]->getMessage(),Response::HTTP_CONFLICT);
        }
        $customerManagement->updateCustomer($customerDTO,$customer);

        return $this->json('Le client est mise à jour avec succès',Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     *
     * @Entity("customer", expr="repository.getCustomer(id)")
     */
    public function delete(Customer $customer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerManagement->deletecCustomer($customer);

        return $this->json('Le client est supprimé avec succès',200,['Content-Type' => 'text/plain']);
    }
}

