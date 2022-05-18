<?php

namespace App\Exception;

use Exception;

class UserException extends Exception
{
    public static function userExists(string $email): UserException
    {
        return new self("L'utilisateur d'adresse $email est déjà existante !");
    }

    public static function notUserExists(): UserException
    {
        return new self("L'utilisateur demandé n'existe pas");
    }
}