<?php

namespace App\Service;

use App\Entity\DTO\UserDTO;
use App\Entity\User;
use App\Exception\UserException;
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
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws UserException
     */
    public function createUser(UserDTO $userDTO, $customer): User
    {
        if ($this->userRepository->findBy(['email' => $userDTO->email])) {
            throw UserException::userExists($userDTO->email);
        }

        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $userDTO->password));

        if (!$customer) {
            $user->setCustomer(null);
        }

        $user->setCustomer($customer);

        foreach ($userDTO->getRoles() as $role) {
            $user->addRole($this->roleRepository->findOneBy(['id' => $role->id]));
        }

        return $this->userRepository->add($user);
    }

    /**
     * @throws UserException
     */
    public function users($customer): array
    {
        if (!$customer) {
            return $this->userRepository->findBy(['customer' => null]);
        }

        $users = $this->userRepository->findBy(['customer' => $customer->getId()]);

        if (!$users) {
            throw UserException::notUserExists();
        }

        return $users;
    }

    /**
     * @throws UserException
     */
    public function updateUser(UserDTO $userDTO, $user, $customer): User
    {
        if (!$customer) {
            $user->setCustomer(null);
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $userDTO->password));
        $user->setCustomer($customer);

        foreach ($userDTO->getRoles() as $role) {
            $user->addRole($this->roleRepository->findOneBy(['id' => $role->id]));
        }

        return $this->userRepository->add($user);
    }

    public function deleteUser($user)
    {
        $this->userRepository->remove($user);
    }
}
