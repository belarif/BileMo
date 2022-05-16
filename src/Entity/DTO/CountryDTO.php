<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CountryDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs nom ne peut pas être vide")
     * @Assert\Length(max=60, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    public string $name;
}
