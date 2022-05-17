<?php

namespace App\Exception;

use Exception;

class ProductException extends Exception
{
    public static function ProductExists(string $name)
    {
        return new self("Le produit $name est déjà existant !");
    }
}