<?php
namespace Community\Query;

use Interop\Container\ContainerInterface;
use Community\Finder\CommunityFinder;

final class FeaturedCommunitiesHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new FeaturedCommunitiesHandler(
            $container->get(CommunityFinder::class)
        );
    }
}
