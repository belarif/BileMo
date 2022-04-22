<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerManagement
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param CustomerDTO $customerDTO
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCustomer(CustomerDTO $customerDTO)
    {
        $customer = new Customer();
        $customer->setCode($customerDTO->code);
        $customer->setStatus($customerDTO->status);
        $customer->setCompany($customerDTO->company);

        $this->customerRepository->add($customer);
    }

    public function customersList(): array
    {
        return $this->customerRepository->findAll();
    }

    /**
     * @param CustomerDTO $customerDTO
     * @param $customer
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateCustomer( CustomerDTO $customerDTO, $customer)
    {

        $customer->setCompany($customerDTO->company);
        $customer->setCode($customerDTO->code);
        $customer->setStatus($customerDTO->status);

        $this->customerRepository->add($customer);
    }

    /**
     * @param $customer
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deletecCustomer($customer)
    {
        $this->customerRepository->remove($customer);
    }
}