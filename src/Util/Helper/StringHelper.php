<?php

namespace App\Util\Helper;

class StringHelper
{
    public static function underscoreToCamelCase(string $input)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($input))));
    }
}
