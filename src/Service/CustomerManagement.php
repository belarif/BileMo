<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Repository\CustomerRepository;

class CustomerManagement
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(CustomerDTO $customerDTO): Customer
    {
        $customer = new Customer();
        $customer->setCode($customerDTO->code);
        $customer->setEnabled($customerDTO->enabled);
        $customer->setCompany($customerDTO->company);

        return $this->customerRepository->add($customer);
    }

    public function customersList(): array
    {
        return $this->customerRepository->findAll();
    }

    public function updateCustomer(CustomerDTO $customerDTO, $customer): Customer
    {

        $customer->setCompany($customerDTO->company);
        $customer->setCode($customerDTO->code);
        $customer->setEnabled($customerDTO->enabled);

        return $this->customerRepository->add($customer);
    }

    public function deletecCustomer($customer)
    {
        $this->customerRepository->remove($customer);
    }
}