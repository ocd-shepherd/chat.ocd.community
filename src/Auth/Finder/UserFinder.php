<?php
namespace Auth\Finder;

use Predis\Client;

class UserFinder
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function findUsernameByUserId($userId)
    {
        return $this->redis->hGet('user:' . $userId, 'username');
    }

    public function findByUserId($userId)
    {
        return (object) $this->redis->hGetAll('user:' . $userId);

    }

    public function findByUsername($username)
    {
        return $this->findByUserId(
            $this->redis->hGet('index:usernames', strtolower($username))
        );
    }

    public function usernameIsAvailable($username)
    {
        return !$this->redis->hExists('index:usernames', strtolower($username));
    }
}
