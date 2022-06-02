<?php

namespace App\Entity\DTO;

use App\Entity\User;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema()
 */
class CustomerDTO
{
    /**
     * @OA\Property(type="integer")
     */
    public int $id;

    /**
     * @OA\Property(type="string", nullable=false)
     */
    public string $code;

    /**
     * @OA\Property(type="boolean", nullable=false)
     */
    public $enabled;

    /**
     * @Assert\NotBlank(message="Le champs société ne peut pas être vide")
     * @Assert\Length(max=60, maxMessage="Le champs société ne doit pas dépasser {{ limit }} caractères")
     *
     * @OA\Property(type="string", nullable=false)
     */
    public string $company;

    /**
     * @OA\Property(type="array")
     */
    public user $users;

    public string $email;

    public string $password;
}
