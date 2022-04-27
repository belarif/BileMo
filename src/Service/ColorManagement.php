<?php

namespace App\Service;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
use App\Repository\ColorRepository;

class ColorManagement
{
    private ColorRepository $colorRepository;

    /**
     * @param ColorRepository $colorRepository
     */
    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    /**
     * @param ColorDTO $colorDTO
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createColor(ColorDTO $colorDTO)
    {
        $color = new Color();
        $color->setName($colorDTO->name);

        $this->colorRepository->add($color);
    }

    /**
     * @return array
     */
    public function colorsList(): array
    {
        return $this->colorRepository->findAll();
    }

    /**
     * @param $color
     * @param ColorDTO $colorDTO
     * @return Void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateColor($color, ColorDTO $colorDTO): Void
    {
        $color->setName($colorDTO->name);

        $this->colorRepository->add($color);
    }
}