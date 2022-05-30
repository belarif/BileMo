<?php

namespace App\Entity\DTO;

use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema()
 */
class ProductDTO
{
    /**
     * @OA\Property(type="integer")
     */
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs nom ne peut pas être vide")
     * @Assert\Length(max=60, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     *
     * @OA\Property(type="string", nullable=false)
     */
    public string $name;

    /**
     * @Assert\NotBlank(message="Le champs description ne peut pas être vide")
     * @Assert\Length(max=600, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     *
     * * @OA\Property(nullable=false)
     */
    public string $description;

    /**
     * @Assert\NotBlank(message="Le champs pays ne peut pas être vide")
     *
     * @OA\Property(nullable=false)
     */
    public CountryDTO $country;

    /**
     * @Assert\NotBlank(message="Le champs marque ne peut pas être vide")
     *
     * @OA\Property(nullable=false)
     */
    public BrandDTO $brand;

    /**
     * @Assert\NotBlank(message="Le champs capacité memoire ne peut pas être vide")
     *
     * @OA\Property(nullable=false)
     */
    public MemoryDTO $memory;

    /**
     * @Assert\NotBlank(message="Le champs utilisateur ne peut pas être vide")
     *
     * @OA\Property(nullable=false)
     */
    public UserDTO $user;

    /**
     * @Assert\NotBlank(message="Le champs couleur ne peut pas être vide")
     *
     * @var ColorDTO[]
     *
     * @OA\Property(nullable=false)
     */
    private array $colors = [];

    private array $images = [];

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
            function ($color) {
                $colorDTO = new ColorDTO();
                $colorDTO->id = $color['id'];

                return $colorDTO;
            },
            $colors
        );
    }

    /**
     * @return ImageDTO[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = array_map(
            function ($image) {
                $imageDTO = new ImageDTO();
                $imageDTO->src = $image['src'];

                return $imageDTO;
            },
            $images
        );
    }
}
