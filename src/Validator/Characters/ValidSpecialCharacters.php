<?php

namespace App\Validator\Characters;

class ValidSpecialCharacters
{
    public static function isValidSpecialCharacters($string)
    {
        return preg_match('/[\'""^£$%&*!()}{@#~?><>,|=_+¬]/', $string) ? false : true;
    }
}
