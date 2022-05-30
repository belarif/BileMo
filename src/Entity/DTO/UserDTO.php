<?php

namespace App\Entity\DTO;

use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema()
 */
class UserDTO
{
    /**
     * @OA\Property(type="integer")
     */
    public int $id;

    /**
     * @Assert\NotBlank(message="L'adresse email est obligatoire")
     * @Assert\Email(message="Veuillez fournir une adreese email valide")
     * @Assert\Length(max=50, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     *
     * @OA\Property(type="string", nullable=false)
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     *
     * @OA\Property(type="string", nullable=false)
     */
    public string $password;

    /**
     * @OA\Property(type="object", nullable=true)
     */
    public CustomerDTO $customer;

    /**
     * @OA\Property(type="array", nullable=false)
     */
    public array $roles = [];

    /**
     * @return RoleDTO[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = array_map(
            function ($role) {
                $roleDTO = new RoleDTO();
                $roleDTO->id = $role['id'];

                return $roleDTO;
            },
            $roles
        );
    }
}
