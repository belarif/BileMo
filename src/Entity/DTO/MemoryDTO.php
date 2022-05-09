<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class MemoryDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="Le champs capacité mémoire ne peut pas être vide")
     * @Assert\Length(max=10, maxMessage="La capacité mémoire ne doit pas dépasser {{ limit }} caractères")
     */
    public string $memoryCapacity;
}
