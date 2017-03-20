<?php
namespace Community\Finder;

use Interop\Container\ContainerInterface;

final class CommunityFinderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CommunityFinder($container->get('redis'));
    }
}

