<?php
namespace Auth\Command;

use Auth\Model\User;
use Auth\Finder\UserFinder;
use EvantSource\AggregateRepository;

final class RegisterUserHandler
{
    private $userFinder;

    private $repository;

    public function __construct(UserFinder $userFinder, AggregateRepository $repository)
    {
        $this->userFinder = $userFinder;
        $this->repository = $repository;
    }

    public function __invoke(RegisterUser $command)
    {
        if (!$this->userFinder->usernameIsAvailable($command->username)) {
            throw new \Exception('Username is already registered.');
        }

        $user = User::register($command->userId, $command->username, $command->passwordHash);

        $this->repository->save($user);
    }
}
