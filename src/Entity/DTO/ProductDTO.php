<?php

namespace App\Entity\DTO;

class ProductDTO
{
    public int $id;

    public string $name;

    public string $description;

    public CountryDTO $country;

    public BrandDTO $brand;

    public MemoryDTO $memory;

    public UserDTO $user;

    /**
     * @var ColorDTO[]
     */
    private array $colors = [];

    /**
     * @return ColorDTO[]
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    public function setColors(array $colors): void
    {
        $this->colors = array_map(
            function ($color){
                $colorDTO = new ColorDTO();
                $colorDTO->id = $color['id'];
                return $colorDTO;
            },
            $colors
        );
    }

}