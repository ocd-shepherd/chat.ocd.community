<?php
declare(strict_types=1);

namespace EvantSource;

class DomainDispatcher
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function dispatch($object)
    {
        $config = $this->container->get('config');

        if (!isset($config['evantsource'][get_class($object)])) {
            throw new \Exception(sprintf('No dispatchees for %s', get_class($object)));
        }

        $listeners = $this->container->get('config')['evantsource'][get_class($object)];

        if (is_string($listeners)) {
            $listener = $this->container->get($listeners);
            return $listener($object);
        }

        if (is_array($listeners)) {
            $results = [];
            foreach ($listeners as $listenerName) {
                $listener = $this->container->get($listenerName);
                $results[] = $listener($object);
            }

            return count($results) == 1 ? $results[0] : $results;
        }
    }
}

