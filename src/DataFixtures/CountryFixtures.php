<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $countries = ['Chine', 'Corée du sud', 'France', 'Japan', 'Usa', 'Vietnam'];

        foreach ($countries as $addCountry) {
            $country = new Country();

            $country->setName($addCountry);

            $manager->persist($country);
            $manager->flush();
        }
    }
}
