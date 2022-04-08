<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;

    public const ROLE_USER = 'ROLE_USER';

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $users = [
            ['email' => 'b.ocine@live.fr', 'password' => 'user1'],
            ['email' => 'exemple@gmail.com', 'password' => 'user2'],
        ];

        foreach ($users as $addUser){
            $user = new User();

            $user->setEmail($addUser['email']);
            $user->setPassword($this->hasher->hashPassword($user,$addUser['password']));
            $user->setRoles([self::ROLE_USER]);

            $manager->persist($user);
            $manager->flush();
        }

    }
}