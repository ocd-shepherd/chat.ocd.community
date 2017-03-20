<?php
namespace EvantSource;

class EventStore
{
    private $appendStore;

    private $serializer;

    public function __construct(PhpAppendStore $appendStore, MessageSerializer $serializer)
    {
        $this->appendStore = $appendStore;
        $this->serializer  = $serializer;
    }

    public function loadEventStream(string $id, int $skip = 0, int $count = null): EventStream
    {
        $eventStream = $this->appendStore->readRecords($id, $skip, $count);

        $events = [];

        foreach ($eventStream as $eventRow) {
            $events[] = $this->serializer->unserialize($eventRow['type'], $eventRow['payload']);
        }

        return new EventStream(count($eventStream), $events);
    }

    public function appendToStream(string $id, int $expectedVersion, $events)
    {
        foreach ($events as $event) {
            $data = $this->serializer->serialize($event);
            $this->appendStore->append($id, $data, ['type' => get_class($event)], $expectedVersion);
            $expectedVersion++;
        }
    }
//            $time = microtime(true);
//            if (false === strpos($time, '.')) {
//                $time .= '.0000';
//            }
//            $this->createdAt = \DateTimeImmutable::createFromFormat('U.u', $time);

}
