<?php
namespace Community\Listener;

use Interop\Container\ContainerInterface;

final class CommunityProjectorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CommunityProjector(
            $container->get('redis')
        );
    }
}



