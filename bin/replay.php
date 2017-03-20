<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$container = require 'config/container.php';

$dispatcher = $container->get(EvantSource\DomainDispatcher::class);

$repository = $container->get(EvantSource\AggregateRepository::class);

$appendStore = new EvantSource\PhpAppendStore('data/event_store');
$serializer = new EvantSource\MessageSerializer;
$eventStore = new EvantSource\EventStore($appendStore, $serializer);

$streams = array_diff(scandir('data/event_store'), ['.','..']);

foreach ($streams as $stream) {
    $eventStream = $eventStore->loadEventStream($stream);

    foreach ($eventStream->events as $event) {
        $dispatcher->dispatch($event);
    }
}
