<?php
namespace Auth\Validator;

use Respect\Validation\Validator as v;
use ZxcvbnPhp\Zxcvbn;

class Password
{
    public static function validator()
    {
        return v::length(6, null)->callback(function($value) {
            return true;
            $zxcvbn = new Zxcvbn();
            $strength = $zxcvbn->passwordStrength($value);
            return ($strength['score'] > 0);
        });
    }
}

