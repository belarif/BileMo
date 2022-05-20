<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\RoleFixtures;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $hasher;
    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $hasher, RoleRepository  $roleRepository)
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
            ['email' => 'example@gmail.com', 'password' => 'admin']
        ];

        foreach ($users as $addUser) {
            $user = new User();

            $user->setEmail($addUser['email']);
            $user->setPassword($this->hasher->hashPassword($user, $addUser['password']));

            $role = $this->roleRepository->findBy(['roleName' => RoleFixtures::ROLE_ADMIN]);
            $user->setRoles($role);

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
