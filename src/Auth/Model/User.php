<?php
declare(strict_types=1);

namespace Auth\Model;

use Auth\Event\UserRegistered;
use Auth\Event\PasswordChanged;
use EvantSource\AggregateRootTrait;

class User
{
    use AggregateRootTrait;

    private $id;

    private $username;

    private $passwordHash;

    private function __construct() {}

    public function aggregateId()
    {
        return $this->id->toString();
    }

    public static function register(string $userId, string $username, string $passwordHash): User
    {
        $user = new self;

        $user->applyEvent(new UserRegistered($userId, $username, $passwordHash));

        return $user;
    }

    public function changePasswordHash($newPasswordHash)
    {
        $this->applyEvent(new PasswordChanged($this->id->toString(), $newPasswordHash));
    }

    public function whenUserRegistered(UserRegistered $event)
    {
        $this->id = UserId::fromString($event->userId);
        $this->username = $event->username;
        $this->passwordHash = PasswordHash::fromHash($event->passwordHash);
    }

    public function whenPasswordChanged(PasswordChanged $event)
    {
        $this->passwordHash = PasswordHash::fromhash($event->newPasswordHash);
    }
}
