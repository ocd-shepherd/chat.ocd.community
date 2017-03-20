<?php
namespace Auth\Command;

use Interop\Container\ContainerInterface;
use EvantSource\AggregateRepository;
use Auth\Finder\UserFinder;

final class RegisterUserHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $userFinder = $container->get(UserFinder::class);
        $repository = $container->get(AggregateRepository::class);

        return new RegisterUserHandler($userFinder, $repository);
    }
}

