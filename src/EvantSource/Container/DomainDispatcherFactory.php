<?php
declare(strict_types=1);

namespace EvantSource\Container;

use Interop\Container\ContainerInterface;
use EvantSource\DomainDispatcher;

class DomainDispatcherFactory
{
    public function __invoke(ContainerInterface $container): DomainDispatcher
    {
        return new DomainDispatcher($container);
    }
}
