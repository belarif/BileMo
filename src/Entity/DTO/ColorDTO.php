<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class ColorDTO
{
    /**
     * @var int
     *
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
}

