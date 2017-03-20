<?php
namespace Common;

use Interop\Container\ContainerInterface;
use Predis;

class RedisConnectionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Predis\Client();
    }
}
