<?php
declare(strict_types=1);

namespace EvantSource;

interface AggregateRoot
{
    public static function restoreFromEventStream(EventStream $eventStream);

    public function applyEvent($event, $old = false);
}
