<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_CUSTOMER_ADMIN = 'ROLE_CUSTOMER_ADMIN';
    public const ROLE_VISITOR = 'ROLE_VISITOR';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $roles = [
            ['roleName' => self::ROLE_ADMIN],
            ['roleName' => self::ROLE_CUSTOMER_ADMIN],
            ['roleName' => self::ROLE_VISITOR]
        ];

        foreach ($roles as $addRole) {
            $role = new Role();

            $role->setRoleName($addRole['roleName']);

            $manager->persist($role);
            $manager->flush();
        }
    }

}
