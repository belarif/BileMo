<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\DTO\CustomerDTO;
use App\Entity\User;
use App\Exception\CustomerException;
use App\Repository\CustomerRepository;
use App\Repository\RoleRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

class CustomerManagement
{
    public const ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    private CustomerRepository $customerRepository;

    private RoleRepository $roleRepository;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        CustomerRepository $customerRepository,
        RoleRepository $roleRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->customerRepository = $customerRepository;
        $this->roleRepository = $roleRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param CustomerDTO $customerDTO
     * @return Customer
     * @throws CustomerException
     */
    public function createCustomer(CustomerDTO $customerDTO): Customer
    {
        if ($this->customerRepository->findBy(['company' => $customerDTO->company])) {
            throw CustomerException::customerExists($customerDTO->company);
        }

        $customer = new Customer();
        $code = new Ulid();

        $customer->setCode($code->toBase58());
        $customer->setEnabled($customerDTO->enabled);
        $customer->setCompany($customerDTO->company);

        $user = new User();

        $user->setEmail($customerDTO->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $customerDTO->password));
        $user->addRole($this->roleRepository->findOneBy(['roleName' => self::ROLE_CUSTOMER]));
        $customer->addUser($user);

        return $this->customerRepository->add($customer);
    }

    /**
     * @throws CustomerException
     */
    public function customersList(): array
    {
        $customers = $this->customerRepository->findAll();

        if (!$customers) {
            throw CustomerException::notCustomerExists();
        }

        return $customers;
    }

    /**
     * @param $customer
     * @param CustomerDTO $customerDTO
     * @return Customer
     */
    public function updateCustomer($customer, CustomerDTO $customerDTO): Customer
    {
        $customer->setEnabled($customerDTO->enabled);

        return $this->customerRepository->add($customer);
    }

    public function deletecCustomer($customer)
    {
        $this->customerRepository->remove($customer);
    }
}
