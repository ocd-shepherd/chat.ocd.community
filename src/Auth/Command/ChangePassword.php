<?php
namespace Auth\Command;

use EvantSource\ReadOnlyProperties;

class ChangePassword
{
    use ReadOnlyProperties;

    private $userId;

    private $newPasswordHash;
}
