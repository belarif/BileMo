<?php

namespace App\Exception;

use Exception;

class MemoryException extends Exception
{
    public static function memoryExists(string $memoryCapacity)
    {
        return new self("La mémoire de capacité $memoryCapacity est déjà existante !");
    }
}