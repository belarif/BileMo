<?php

namespace App\Exception;

use Exception;

class MemoryException extends Exception
{
    public static function memoryExists(string $memoryCapacity): MemoryException
    {
        return new self("La mémoire de capacité $memoryCapacity est déjà existante !");
    }

    public static function notMemoryExists(): MemoryException
    {
        return new self("La capacité memoire demandé n'existe pas");
    }
}