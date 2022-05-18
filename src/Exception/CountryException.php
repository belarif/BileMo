<?php

namespace App\Exception;

use Exception;

class CountryException extends Exception
{
    public static function countryExists(string $country)
    {
        return new self("Le pays $country est déjà existante !");
    }

    public static function notCountryExists()
    {
        return new self("Le pays demandé n'existe pas");
    }
}