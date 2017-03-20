<?php
namespace Community\Finder;

use Predis\Client;

class CommunityFinder
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function findRandomMembers($id, $n = 10)
    {
        $userKeys = $this->redis->sRandMember("community:members:{$id}", $n);

        $redis = $this->redis;
        $members = array_map(function($key) use ($redis) {
            return (object) $redis->hGetAll($key);
        }, $userKeys);

        return $members;
    }

    public function findById($id)
    {
        return (object) $this->redis->hGetAll('community:' . $id);
    }

    public function findByPath($path)
    {
        return $this->findById(
            $this->redis->hGet('index:community-paths', $path)
        );
    }

    public function findIdByPath($path)
    {
        return $this->redis->hGet('index:community-paths', $path);
    }

    public function pathIsAvailable($path)
    {
        return !$this->redis->hExists('index:community-paths', $path);
    }

    public function findAllFeatured()
    {
        $ids = $this->redis->zRange('index:featured-communities', 0, -1);

        $communities = [];

        foreach ($ids as $id) {
            $communities[] = $this->findById($id);
        }

        return $communities;
    }
}
