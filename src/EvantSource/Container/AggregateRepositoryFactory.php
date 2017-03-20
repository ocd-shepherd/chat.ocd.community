<?php
namespace EvantSource\Container;

use Interop\Container\ContainerInterface;
use EvantSource\EventStore;
use EvantSource\PhpAppendStore;
use EvantSource\AggregateRepository;
use EvantSource\DomainDispatcher;
use EvantSource\MessageSerializer;

class AggregateRepositoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $appendStore = new PhpAppendStore('data/event_store');
        $serializer = new MessageSerializer;
        $eventStore = new EventStore($appendStore, $serializer);
        $dispatcher = $container->get(DomainDispatcher::class);
        return new AggregateRepository($eventStore, $dispatcher);
    }
}
