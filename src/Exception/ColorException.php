<?php

namespace App\Exception;

use Exception;

class ColorException extends Exception
{
    public static function colorExists(string $color)
    {
        return new self("La couleur $color est déjà existante !");
    }

    public static function notColorExists()
    {
        return new self("La couleur demandée n'existe pas");
    }
}