<?php

namespace Utils;

class ZipCodeUtils
{
    public static function isZipCodeValid(string $zipCode): bool
    {
        $zipRegex = "/^[0-9]{5}(-[0-9]{4})?$/";
        return preg_match($zipRegex, $zipCode);
    }
}
