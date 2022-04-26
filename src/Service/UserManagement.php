<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\DTO\UserDTO;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManagement
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param UserDTO $userDTO
     * @param $customer
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser(UserDTO $userDTO, $customer)
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));
        $user->setCustomer($customer);
        $user->setRoles($userDTO->roles);

        $this->userRepository->add($user);
    }

    /**
     * @param $customer
     * @return array
     */
    public function usersList($customer): array
    {
        return $this->userRepository->findBy(['customer' => $customer->getId()]);
    }

    /**
     * @param $user_id
     * @param $customer
     * @return User|null
     */
    public function showUser($user_id, $customer): User
    {
        return $this->userRepository->findOneBy(['id' => $user_id, 'customer' => $customer]);
    }

    /**
     * @param UserDTO $userDTO
     * @param $user
     * @param $customer
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser(UserDTO $userDTO, $user, $customer)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user,$userDTO->password));
        $user->setCustomer($customer);

        $this->userRepository->add($user);
    }
}