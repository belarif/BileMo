<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Exception\CustomerException;
use App\Repository\CustomerRepository;
use Symfony\Component\Uid\Ulid;

class CustomerManagement
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws CustomerException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createCustomer(CustomerDTO $customerDTO): Customer
    {
        if($this->customerRepository->findBy(['company' => $customerDTO->company])) {
            throw CustomerException::customerExists($customerDTO->company);
        }

        $customer = new Customer();
        $code = new Ulid();

        $customer->setCode($code->toBase58());
        $customer->setEnabled($customerDTO->enabled);
        $customer->setCompany($customerDTO->company);

        return $this->customerRepository->add($customer);
    }

    public function customersList(): array
    {
        return $this->customerRepository->findAll();
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws CustomerException
     */
    public function updateCustomer( $customer, CustomerDTO $customerDTO): Customer
    {
        if($this->customerRepository->findBy(['company' => $customerDTO->company])) {
            throw CustomerException::customerExists($customerDTO->company);
        }

        $customer->setCompany($customerDTO->company);
        $customer->setEnabled($customerDTO->enabled);

        return $this->customerRepository->add($customer);
    }

    public function deletecCustomer($customer)
    {
        $this->customerRepository->remove($customer);
    }
}
