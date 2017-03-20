<?php
namespace Chat\Listener;

use Interop\Container\ContainerInterface;
use Auth\Finder\UserFinder;

class MessageProjectorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new MessageProjector(
            $container->get('redis'),
            $container->get(UserFinder::class)
        );
    }
}
