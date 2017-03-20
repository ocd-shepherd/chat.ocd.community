<?php
namespace Community\Query;

use Interop\Container\ContainerInterface;
use Community\Finder\CommunityFinder;

final class UsersInCommunityHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new UsersInCommunityHandler(
            $container->get(CommunityFinder::class)
        );
    }
}

