<?php

namespace App\Exception;

use Exception;

class CountryException extends Exception
{
    public static function countryExists(string $country)
    {
        return new self("Le pays $country est déjà existante !");
    }
}