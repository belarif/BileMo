<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $brands = ['Huawei', 'Iphone', 'Motorola', 'Oppo', 'Samsung', 'Ulefone', 'Xiaomi'];

        foreach ($brands as $addBrand) {
            $brand = new Brand();

            $brand->setName($addBrand);

            $manager->persist($brand);
            $manager->flush();
        }
    }
}
