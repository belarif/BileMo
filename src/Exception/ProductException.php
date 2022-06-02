<?php

namespace App\Exception;

use Exception;

class ProductException extends Exception
{
    public static function ProductExists(string $name): ProductException
    {
        return new self("Le produit $name est déjà existant !");
    }

    public static function notProductExists(): ProductException
    {
        return new self("Aucun produit trouvé");
    }
}
