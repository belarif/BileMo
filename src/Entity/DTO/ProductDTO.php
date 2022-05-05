<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs nom ne peut pas être vide")
     * @Assert\Length(max=60, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    public string $name;

    /**
     * @Assert\NotBlank(message="Le champs description ne peut pas être vide")
     * @Assert\Length(max=600, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    public string $description;

    /**
     * @Assert\NotBlank(message="Le champs pays ne peut pas être vide")
     */
    public CountryDTO $country;

    /**
     * @Assert\NotBlank(message="Le champs marque ne peut pas être vide")
     */
    public BrandDTO $brand;

    /**
     * @Assert\NotBlank(message="Le champs capacité memoire ne peut pas être vide")
     */
    public MemoryDTO $memory;

    /**
     * @Assert\NotBlank(message="Le champs utilisateur ne peut pas être vide")
     */
    public UserDTO $user;

    /**
     * @Assert\NotBlank(message="Le champs couleur ne peut pas être vide")
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