<?php
declare(strict_types=1);

namespace Community\Event;

use EvantSource\ReadOnlyProperties;

class CommunityCreated
{
    use ReadOnlyProperties;

    private $communityId;

    private $userId;

    private $name;

    private $path;

    public function __construct(
        string $communityId,
        string $userId,
        string $name,
        string $path
    )
    {
        $this->communityId = $communityId;
        $this->userId      = $userId;
        $this->name        = $name;
        $this->path        = $path;
    }
}

