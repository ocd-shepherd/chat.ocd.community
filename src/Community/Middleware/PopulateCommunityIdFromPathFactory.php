<?php
namespace Community\Middleware;

use Interop\Container\ContainerInterface;
use Community\Finder\CommunityFinder;

class PopulateCommunityIdFromPathFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PopulateCommunityIdFromPath(
            $container->get(CommunityFinder::class)
        );
    }
}
