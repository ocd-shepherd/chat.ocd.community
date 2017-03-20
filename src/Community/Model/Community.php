<?php
namespace Community\Model;

use EvantSource\ReadOnlyProperties;
use EvantSource\AggregateRootTrait;

use Community\Event\CommunityCreated;

class Community
{
    use AggregateRootTrait;
    use ReadOnlyProperties;

    private $id;

    private $ownerId;

    private $name;

    private $path;

    public function aggregateId()
    {
        return $this->id;
    }

    public static function create(
        string $id,
        string $ownerId,
        string $name,
        string $path
    )
    {
        $self = new self;

        $self->applyEvent(new CommunityCreated($id, $ownerId, $name, $path));

        return $self;
    }

    public function whenCommunityCreated(CommunityCreated $e)
    {
        $this->id      = $e->communityId;
        $this->ownerId = $e->userId;
        $this->name    = $e->name;
        $this->path    = $e->path;
    }
}

