<?php
namespace Auth\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class PopulateCurrentUserIdFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PopulateCurrentUserId(
            $container->get(AuthService::class)
        );
    }
}
