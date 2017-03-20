<?php
declare(strict_types=1);

namespace Community\Command;

use EvantSource\ReadOnlyProperties;

class CreateCommunity
{
    use ReadOnlyProperties;

    private $communityId;

    private $userId;

    private $name;

    private $path;

    private function __construct(){}
}
