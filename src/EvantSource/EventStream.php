<?php
declare(strict_types=1);

namespace EvantSource;

class EventStream
{
    use ReadOnlyProperties;

    private $version;

    private $events;

    public function __construct(int $version, array $events)
    {
        $this->version = $version;
        $this->events  = $events;
    }
}
