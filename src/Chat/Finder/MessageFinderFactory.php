<?php
namespace Chat\Finder;

use Interop\Container\ContainerInterface;

final class MessageFinderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new MessageFinder($container->get('redis'));
    }
}
