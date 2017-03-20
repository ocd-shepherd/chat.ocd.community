<?php
declare(strict_types=1);

namespace EvantSource;

class MessageSerializer
{
    public function serialize($object)
    {
        $array = [];
        $key = "\0" . get_class($object) . "\0";
        $properties = (array) $object;

        foreach ($properties as $key => $value) {
            $key = str_replace("\0" . get_class($object) . "\0", '', $key);
            if ($key[0].$key[1] === '__') continue;
            if (!is_scalar($value)) {
                throw new \Exception('Non-scalar value found in event or command object');
            }
            $array[$key] = $value;
        }

        return json_encode($array);
    }

    public function unserialize($class, $properties)
    {
        $instance = (new \ReflectionClass($class))->newInstanceWithoutConstructor();

        if (is_string($properties)) {
            $properties = json_decode($properties, true);
        }

        if (!is_array($properties)) {
            throw new \Exception('Invalid properties passed');
        }

        foreach ($properties as $name => $value) {
            if ($name[0].$name[1] === '__') continue;
            $reflection = new \ReflectionProperty(get_class($instance), $name);
            $reflection->setAccessible(true);
            $reflection->setValue($instance, $value);
        }

        return $instance;
    }
}
