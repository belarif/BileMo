<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Service\CustomerManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/customers", name="api_", requirements={"customer_id"="\d+"})
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("", name="create_customer", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(),CustomerDTO::class,'json');

        $customerManagement->createCustomer($customerDTO);

        return $this->json("Le client a été créé avec succès",200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("", name="customers_list", methods={"GET"})
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->json($customerManagement->customersList(),200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{customer_id}", name="show_customer", methods={"GET"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->json($customer,200,['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{customer_id}", name="update_customer", methods={"PUT"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function update(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement, Customer $customer): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');

        $customerManagement->updateCustomer($customerDTO,$customer);

        return $this->json('Le client est mise à jour avec succès',200,['Content-Type' => 'text/plain']);
    }

    /**
     * @Route("/{customer_id}", name="delete_customer", methods={"DELETE"})
     *
     * @Entity("customer", expr="repository.getCustomer(customer_id)")
     */
    public function delete(Customer $customer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerManagement->deletecCustomer($customer);

        return $this->json('Le client est supprimé avec succès',200,['Content-Type' => 'text/plain']);
    }
}

