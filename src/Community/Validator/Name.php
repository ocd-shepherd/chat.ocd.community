<?php
namespace Community\Validator;

use Respect\Validation\Validator as v;

class Name
{
    public static function validator()
    {
        return v::alnum('-_')->length(3, 25);
    }
}

