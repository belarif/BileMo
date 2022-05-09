<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\DTO\UserDTO;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManagement
{
    private UserRepository $userRepository;

    private RoleRepository $roleRepository;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(UserDTO $userDTO, $customer)
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));

        if(!$customer) {
            $user->setCustomer(null);
        }

        $user->setCustomer($customer);

        foreach ($userDTO->getRoles() as $role) {
            $user->addRole($this->roleRepository->findOneBy(['id' => $role->id]));
        }

        $this->userRepository->add($user);
    }

    public function users($customer): array
    {
        if(!$customer) {
            return $this->userRepository->getAdmins();
        }

        return $this->userRepository->findBy(['customer' => $customer->getId()]);
    }


    public function showUser($user_id, $customer): User
    {
        return $this->userRepository->findOneBy(['id' => $user_id, 'customer' => $customer]);
    }

    public function updateUser(UserDTO $userDTO, $user, $customer)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));

        if(!$customer) {
            $user->setCustomer(null);
        }

        $user->setCustomer($customer);

        $this->userRepository->add($user);
    }

    public function deleteUser($user)
    {
        $this->userRepository->remove($user);
    }
}