<?php
declare(strict_types=1);

namespace EvantSource;

trait AggregateRootTrait
{
    private $__loadedEventStream;

    private $__newEvents = [];

    public function newEvents(): array
    {
        return $this->__newEvents;
    }

    public function flushEvents()
    {
        $this->__newEvents = [];
    }

    public function getLoadedEventStream(): EventStream
    {
        if (!$this->__loadedEventStream) {
            $this->__loadedEventStream = new EventStream(0, []);
        }

        return $this->__loadedEventStream;
    }

    public static function restoreFromEventStream(EventStream $eventStream)
    {
        $self = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();

        foreach ($eventStream->events as $event) {
            $self->applyEvent($event, true);
        }

        $self->__loadedEventStream = $eventStream;

        return $self;
    }

    public function applyEvent($event, $old = false)
    {
        $handlerMethod = 'when' . implode('', array_slice(explode('\\', get_class($event)), -1));

        if (! method_exists($this, $handlerMethod)) {
            throw new \RuntimeException(sprintf(
                "Missing event handler method %s for aggregate root %s",
                $handlerMethod,
                get_class($this)
            ));
        }

        $this->{$handlerMethod}($event);

        if (!$old) {
            $this->__newEvents[] = $event;
        }
    }
}
