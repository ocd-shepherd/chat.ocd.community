<?php
declare(strict_types=1);

namespace Auth\Listener;

use Predis\Client;
use Auth\Event\UserRegistered;
use Auth\Event\PasswordChanged;
use Chat\Command\ChangeAvatar as AvatarChanged;

class UserProjector
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function __invoke($event)
    {
        switch(get_class($event)) {
            case UserRegistered::class:
                $this->onUserRegistered($event);
                break;
            case PasswordChanged::class:
                $this->onPasswordChanged($event);
                break;
            case AvatarChanged::class:
                return $this->onAvatarChanged($event);
                break;
        }
    }

    public function onAvatarChanged(AvatarChanged $e)
    {
        $current = $this->redis->hGet(
            'user:' . $e->userId,
            'avatar'
        ) ?: '';

        if (strpos($current, 'avatars') !== false) {
            @unlink('public' . $current);
        }

        $this->redis->hSet(
            'user:' . $e->userId,
            'avatar',
            $e->mediaPath
        );

        return true;
    }

    public function onPasswordChanged(PasswordChanged $e)
    {
        $this->redis->hSet(
            'user:' . $e->userId,
            'passwordHash',
            $e->newPasswordHash
        );
    }

    public function onUserRegistered(UserRegistered $e)
    {
        $this->redis->hSet('index:usernames', strtolower($e->username), $e->userId);
        $this->redis->hMSet('user:' . $e->userId,
            [
            'id'           => $e->userId,
            'username'     => $e->username,
            'passwordHash' => $e->passwordHash,
        ]);
    }
}
