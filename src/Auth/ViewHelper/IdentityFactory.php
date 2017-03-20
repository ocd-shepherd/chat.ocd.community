<?php
namespace Auth\ViewHelper;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class IdentityFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Identity(
            $container->get(AuthService::class)
        );
    }
}
