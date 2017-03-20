<?php
namespace Auth\Validator;

use Respect\Validation\Validator as v;

class RegisterUser
{
    public static function validator()
    {
        return v::key('username', Username::validator())
                ->key('password', Password::validator());
    }
}
