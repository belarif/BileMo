<?php

namespace App\Service;

use App\Entity\Color;
use App\Entity\DTO\ColorDTO;
use App\Exception\ColorException;
use App\Repository\ColorRepository;

class ColorManagement
{
    private ColorRepository $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    /**
     * @throws ColorException
     */
    public function createColor(ColorDTO $colorDTO): Color
    {
        if ($this->colorRepository->findBy(['name' => $colorDTO->name])) {
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
     * @throws ColorException
     */
    public function updateColor($color, ColorDTO $colorDTO): Color
    {
        if ($this->colorRepository->findBy(['name' => $colorDTO->name])) {
            throw ColorException::colorExists($colorDTO->name);
        }

        return $this->colorRepository->add($color->setName($colorDTO->name));
    }

    /**
     * @param $color
     *
     * @return void
     */
    public function deleteColor($color)
    {
        $this->colorRepository->remove($color);
    }
}
