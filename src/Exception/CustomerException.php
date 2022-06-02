<?php

namespace App\Exception;

use Exception;

class CustomerException extends Exception
{
    public static function customerExists(string $company): CustomerException
    {
        return new self("Le client $company est déjà existant !");
    }

    public static function notCustomerExists(): CustomerException
    {
        return new self('Aucun client trouvé');
    }
}
