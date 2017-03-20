<?php
declare(strict_types=1);

namespace Community\Listener;

use Community\Event\CommunityCreated;

use Predis\Client;

class CommunityProjector
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function __invoke($event)
    {
        switch(get_class($event)) {
            case CommunityCreated::class:
                $this->whenCommunityCreated($event);
                break;
        }
    }

    public function whenCommunityCreated(CommunityCreated $e)
    {
        $this->redis->hSet('index:community-paths', $e->path, $e->communityId);
        $this->redis->hMSet('community:' . $e->communityId,
            [
            'path'  => $e->path,
            'name'  => $e->name,
            'owner' => $e->userId,
        ]);
    }

}
