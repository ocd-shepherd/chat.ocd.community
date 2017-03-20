<?php
namespace Auth\Query;

use Interop\Container\ContainerInterface;
use Auth\Finder\UserFinder;

final class UsernameAvailableHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $userFinder = $container->get(UserFinder::class);

        return new UsernameAvailableHandler($userFinder);
    }
}
