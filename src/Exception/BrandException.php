<?php

namespace App\Exception;

use Exception;

class BrandException extends Exception
{
    public static function brandExists(string $brand): BrandException
    {
        return new self("La marque $brand est déjà existante !");
    }

    public static function notBrandExists(): BrandException
    {
        return new self("La marque demandée n'existe pas");
    }
}