<?php
declare(strict_types=1);

namespace Auth\Event;

use EvantSource\ReadOnlyProperties;

class PasswordChanged
{
    use ReadOnlyProperties;

    private $userId;

    private $newPasswordHash;

    public function __construct(
        string $userId,
        string $newPasswordhash
    )
    {
        $this->userId          = $userId;
        $this->newPasswordHash = $newPasswordhash;
    }
}
