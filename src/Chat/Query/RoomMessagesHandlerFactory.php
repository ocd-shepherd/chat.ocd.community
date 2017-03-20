<?php
namespace Chat\Query;

use Interop\Container\ContainerInterface;
use Chat\Finder\MessageFinder;

final class RoomMessagesHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RoomMessagesHandler(
            $container->get(MessageFinder::class)
        );
    }
}
