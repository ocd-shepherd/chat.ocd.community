<?php
namespace Common\Middleware;

use Interop\Container\ContainerInterface;

class ProxyMediaFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ProxyMedia;
    }
}
