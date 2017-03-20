<?php
namespace Auth\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class DeauthenticateFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Deauthenticate(
            $container->get(AuthService::class)
        );
    }
}
