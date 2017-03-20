<?php
namespace Common;

use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

class FactoryResolverAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $serviceLocator, $requestedName)
    {
        return class_exists($this->getFactoryClass($requestedName));
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factoryClass = '\\' . $this->getFactoryClass($requestedName);
        $factory = new $factoryClass;
        return $factory($container);
    }

    private function getFactoryClass($class)
    {
        if (class_exists($class . 'Factory')) {
            return $class . 'Factory';
        }

        $classNameParts = explode('\\', $class);
        $topNamespace = array_shift($classNameParts);
        array_unshift($classNameParts, 'Container');
        array_unshift($classNameParts, $topNamespace);
        return implode('\\', $classNameParts) . 'Factory';
    }
}
