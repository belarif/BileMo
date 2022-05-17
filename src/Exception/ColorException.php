<?php

namespace App\Exception;

use Exception;

class ColorException extends Exception
{
    public static function colorExists(string $color)
    {
        return new self("La couleur $color est déjà existante !");
    }
}