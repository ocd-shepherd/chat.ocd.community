<?php
namespace Auth\Query;

use Auth\Finder\UserFinder;

final class UsernameAvailableHandler
{
    private $userFinder;

    public function __construct(UserFinder $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function __invoke(UsernameAvailable $query)
    {
        $available = $this->userFinder->usernameIsAvailable($query->username);

        return $available;
    }
}
