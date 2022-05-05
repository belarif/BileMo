<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ColorDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs nom ne peut pas être vide")
     * @Assert\Length(max=100, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    public string $name;
}
