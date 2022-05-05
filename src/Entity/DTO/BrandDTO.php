<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class BrandDTO
{

    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs nom est obligatoire")
     */
    public string $name;
}
