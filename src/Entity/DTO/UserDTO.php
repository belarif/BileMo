<?php

namespace App\Entity\DTO;

use App\Entity\Customer;

class UserDTO
{
    public int $id;

    public string $email;

    public string $password;

    public CustomerDTO $customer;

    public array $roles;

}