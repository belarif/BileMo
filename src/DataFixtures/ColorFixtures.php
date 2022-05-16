<?php

namespace App\DataFixtures;

use App\Entity\Color;
use Doctrine\Persistence\ObjectManager;

class ColorFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $colors = ['Blanc', 'Bleu', 'Doré', 'Gris argent', 'Noir', 'Rose'];

        foreach ($colors as $addColor) {
            $color = new Color();

            $color->setName($addColor);

            $manager->persist($color);
            $manager->flush();
        }
    }
}
