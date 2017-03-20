<?php
declare(strict_types=1);

namespace EvantSource;

trait ReadOnlyProperties
{
    public function __get($name)
    {
        if (!isset($this->{$name})) {
            throw new \Exception("No property called {$name}");
        }

        return $this->{$name};
    }
}
