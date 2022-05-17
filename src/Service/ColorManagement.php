<?php

namespace App\Service;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
use App\Repository\ColorRepository;
use App\Exception\ColorException;

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
    public function createColor(ColorDTO $colorDTO): Color
    {
        if($this->colorRepository->findBy(['name' => $colorDTO->name])) {
            throw ColorException::colorExists($colorDTO->name);
        }

        $color = new Color();
        $color->setName($colorDTO->name);

        return $this->colorRepository->add($color);
    }

    public function colorsList(): array
    {
        return $this->colorRepository->findAll();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws ColorException
     */
    public function updateColor($color, ColorDTO $colorDTO): Color
    {
        if($this->colorRepository->findBy(['name' => $colorDTO->name])) {
            throw ColorException::colorExists($colorDTO->name);
        }

        return $this->colorRepository->add($color->setName($colorDTO->name));
    }

    /**
     * @param $color
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteColor($color)
    {
        $this->colorRepository->remove($color);
    }
}
