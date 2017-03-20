<?php
namespace Auth\Validator;

use Respect\Validation\Validator as v;

class ChangePassword
{
    public static function validator()
    {
        return v::key('password', Password::validator());
    }
}
