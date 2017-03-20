<?php
namespace Common\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class PersistMediaFromRedisFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PersistMediaFromRedis(
            $container->get('redis'),
            $container->get(AuthService::class)
        );
    }
}
