<?php
namespace Auth\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class AuthenticateFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Authenticate(
            $container->get(AuthService::class)
        );
    }
}
