<?php
namespace EvantSource;

class AggregateRepository
{
    private $eventStore;

    private $eventDispatcher;

    public function __construct(EventStore $eventStore, DomainDispatcher $dispatcher)
    {
        $this->eventStore = $eventStore;
        $this->dispatcher = $dispatcher;
    }

    public function getById($class, $id)
    {
        $eventStream = $this->eventStore->loadEventStream($id, 0, 9999);

        return $class::restoreFromEventStream($eventStream);
    }

    public function save($aggregate)
    {
        $expectedVersion = $aggregate->getLoadedEventStream()->version;

        $newEvents = $aggregate->newEvents();

        $this->eventStore->appendToStream($aggregate->aggregateId(), $expectedVersion, $newEvents);

        foreach ($newEvents as $event) {
            $this->dispatcher->dispatch($event);
        }

        $aggregate->flushEvents();
    }
}
