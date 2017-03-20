<?php
namespace Auth;

use Interop\Container\ContainerInterface;
use Auth\Finder\UserFinder;

class AuthServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AuthService(
            $container->get(UserFinder::class),
            $container->get('session')
        );
    }
}
