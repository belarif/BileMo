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

/**
 * @Route("/customers", name="api_")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("", name="create_customer", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param CustomerManagement $customerManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(),CustomerDTO::class,'json');

        $customerManagement->createCustomer($customerDTO);

        return new JsonResponse("Le client a été créé avec succès");
    }

    /**
     * @Route("", name="customers_list", methods={"GET"})
     * @param CustomerManagement $customerManagement
     * @return JsonResponse
     */
    public function list(CustomerManagement $customerManagement): JsonResponse
    {
        return $this->json($customerManagement->customersList(),'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_customer", methods={"GET"})
     * @param Customer $customer
     * @return JsonResponse
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->json($customer,'200',['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param CustomerManagement $customerManagement
     * @param Customer $customer
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request, SerializerInterface $serializer, CustomerManagement $customerManagement, Customer $customer): JsonResponse
    {
        $customerDTO = $serializer->deserialize($request->getContent(), CustomerDTO::class, 'json');
        $customerManagement->updateCustomer($customerDTO,$customer);

        return $this->json('Le client est mise à jour avec succès');
    }

    /**
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     * @param Customer $customer
     * @param CustomerManagement $customerManagement
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Customer $customer, CustomerManagement $customerManagement): JsonResponse
    {
        $customerManagement->deletecCustomer($customer);

        return new JsonResponse('Le client est supprimé avec succès');
    }

}
