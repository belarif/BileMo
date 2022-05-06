<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $roles = ['ROLE_ADMIN','ROLE_VISITOR'];

        foreach($roles as $addRole) {
            $role = new Role();

            $role->setRoleName($addRole);

            $manager->persist($role);
            $manager->flush();
        }
    }
}
