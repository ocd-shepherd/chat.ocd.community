<?php
namespace Community\Command;

use Interop\Container\ContainerInterface;
use EvantSource\AggregateRepository;
use Community\Finder\CommunityFinder;

final class CreateCommunityHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new CreateCommunityHandler(
            $container->get(CommunityFinder::class),
            $container->get(AggregateRepository::class)
        );
    }
}
