<?php

namespace App\Exception;

use Exception;

class BrandException extends Exception
{
    public static function brandExists(string $brand)
    {
        return new self("La marque $brand est déjà existante !");
    }
}