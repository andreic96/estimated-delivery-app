<?php

namespace Exception;

use Exception;

class InvalidDateTimeException extends Exception
{

    public function __construct()
    {
        parent::__construct('Invalid date time Input');
    }

}
