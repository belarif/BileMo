<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Repository\CustomerRepository;
use Symfony\Component\Uid\Ulid;

class CustomerManagement
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(CustomerDTO $customerDTO)
    {
        $customer = new Customer();
        $code = new Ulid();

        $customer->setCode($code->toBase58());
        $customer->setEnabled($customerDTO->enabled);
        $customer->setCompany($customerDTO->company);

        $this->customerRepository->add($customer);
    }

    public function customersList(): array
    {
        return $this->customerRepository->findAll();
    }

    public function updateCustomer( CustomerDTO $customerDTO, $customer)
    {

        $customer->setCompany($customerDTO->company);
        $customer->setCode($customerDTO->code);
        $customer->setEnabled($customerDTO->enabled);

        $this->customerRepository->add($customer);
    }

    public function deletecCustomer($customer)
    {
        $this->customerRepository->remove($customer);
    }
}
