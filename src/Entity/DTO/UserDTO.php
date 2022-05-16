<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public int $id;

    /**
     * @Assert\NotBlank(message="L'adresse email est obligatoire")
     * @Assert\Email(message="Veuillez fournir une adreese email valide")
     * @Assert\Length(max=50, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     */
    public string $password;

    public CustomerDTO $customer;

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
