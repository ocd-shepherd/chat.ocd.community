<?php
declare(strict_types=1);

namespace Auth\Query;

use EvantSource\ReadOnlyProperties;

class UsernameAvailable
{
    use ReadOnlyProperties;

    private $username;
}
