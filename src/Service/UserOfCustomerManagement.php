<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\DTO\UserDTO;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserOfCustomerManagement
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUserOfCustomer(UserDTO $userDTO, $customer)
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));
        $user->setCustomer($customer);
        $user->setRoles($userDTO->roles);

        $this->userRepository->add($user);
    }

    public function usersOfCustomer($customer): array
    {
        return $this->userRepository->findBy(['customer' => $customer->getId()]);
    }

    public function showUserOfCustomer($user_id, $customer): User
    {
        return $this->userRepository->findOneBy(['id' => $user_id, 'customer' => $customer]);
    }

    public function updateUserOfCustomer(UserDTO $userDTO, $user, $customer)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));
        $user->setCustomer($customer);

        $this->userRepository->add($user);
    }

    public function deleteUserOfCustomer($user)
    {
        $this->userRepository->remove($user);
    }
}