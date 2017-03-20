<?php
namespace Auth\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class AssertIsAuthenticatedFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AssertIsAuthenticated(
            $container->get(AuthService::class)
        );
    }
}
