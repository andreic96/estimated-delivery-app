<?php

namespace Exception;

use Exception;

class InvalidZipCodeException extends Exception
{

    public function __construct()
    {
        parent::__construct('Invalid ZipCode Input');
    }

}
