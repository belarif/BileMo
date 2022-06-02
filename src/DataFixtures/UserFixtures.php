<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $hasher;
    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $hasher, RoleRepository $roleRepository)
    {
        $this->hasher = $hasher;
        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            ['email' => 'admin1@gmail.com', 'password' => 'admin1', 'role' => $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_ADMIN])],
            ['email' => 'customer1@gmail.com', 'password' => 'customer1', 'role' => $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_CUSTOMER])],
            ['email' => 'visitor1@gmail.com', 'password' => 'visitor1', 'role' => $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_VISITOR])]
        ];

        foreach ($users as $addUser) {
            $user = new User();

            $user->setEmail($addUser['email']);
            $user->setPassword($this->hasher->hashPassword($user, $addUser['password']));
            $user->setRoles($addUser['role']);

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            RoleFixtures::class,
        ];
    }
}
