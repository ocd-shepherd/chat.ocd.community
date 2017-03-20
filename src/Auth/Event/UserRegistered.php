<?php
declare(strict_types=1);

namespace Auth\Event;

use EvantSource\ReadOnlyProperties;

class UserRegistered
{
    use ReadOnlyProperties;

    private $userId;

    private $username;

    private $passwordHash;

    public function __construct(
        string $userId,
        string $username,
        string $passwordhash
    )
    {
        $this->userId       = $userId;
        $this->username     = $username;
        $this->passwordHash = $passwordhash;
    }
}
