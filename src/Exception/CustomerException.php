<?php

namespace App\Exception;

use Exception;

class CustomerException extends Exception
{
    public static function customerExists(string $company)
    {
        return new self("Le client $company est déjà existant !");
    }
}