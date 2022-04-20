<?php

namespace App\DataFixtures;

use App\Entity\Memory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MemoryFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $memories = ['16Go','32Go','64Go','128Go','256Go'];

        foreach ($memories as $addMemory) {
            $memory = new Memory();

            $memory->setMemoryCapacity($addMemory);

            $manager->persist($memory);
            $manager->flush();
        }
    }
}
