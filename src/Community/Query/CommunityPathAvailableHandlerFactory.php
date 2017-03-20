<?php
namespace Community\Query;

use Interop\Container\ContainerInterface;
use Community\Finder\CommunityFinder;

final class CommunityPathAvailableHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CommunityPathAvailableHandler(
            $container->get(CommunityFinder::class)
        );
    }
}

