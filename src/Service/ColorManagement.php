<?php

namespace App\Service;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
use App\Repository\ColorRepository;

class ColorManagement
{
    private ColorRepository $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createColor(ColorDTO $colorDTO):void
    {
        $color = new Color();
        $color->setName($colorDTO->name);

        $this->colorRepository->add($color);
    }

    public function colorsList(): array
    {
        return $this->colorRepository->findAll();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateColor($color, ColorDTO $colorDTO): Void
    {
        $color->setName($colorDTO->name);

        $this->colorRepository->add($color);
    }

    /**
     * @param $color
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteColor($color)
    {
        $this->colorRepository->remove($color);
    }
}