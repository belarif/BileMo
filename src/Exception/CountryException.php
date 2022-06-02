<?php

namespace App\Exception;

use Exception;

class CountryException extends Exception
{
    public static function countryExists(string $country): CountryException
    {
        return new self("Le pays $country est déjà existante !");
    }

    public static function notCountryExists(): CountryException
    {
        return new self('Aucun pays trouvé');
    }
}
