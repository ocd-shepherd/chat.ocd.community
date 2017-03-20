<?php
namespace Community\Validator;

use Respect\Validation\Validator as v;

class CreateCommunity
{
    public static function validator()
    {
        return v::key('name', Name::validator())
                ->key('path', Path::validator());
    }
}


