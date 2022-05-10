<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs code ne peut pas être vide")
     */
    public string $code;

    public $enabled;

    /**
     * @Assert\NotBlank(message="Le champs société ne peut pas être vide")
     * @Assert\Length(max=60, maxMessage="Le champs société ne doit pas dépasser {{ limit }} caractères")
     */
    public string $company;
}
