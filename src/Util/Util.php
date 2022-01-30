<?php

namespace App\Util;

abstract class Util
{
    /**
     * méthode qui retourne un tableau à partir d'une chaîne
     *
     * @param string $string
     * @return array
     */
    public static function splitArray(string $string): array
    {
        return explode(', ', $string);
    }
}