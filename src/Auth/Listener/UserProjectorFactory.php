<?php
namespace Auth\Listener;

use Interop\Container\ContainerInterface;

final class UserProjectorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new UserProjector(
            $container->get('redis')
        );
    }
}


