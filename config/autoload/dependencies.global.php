<?php
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'abstract_factories' => [
            Common\FactoryResolverAbstractFactory::class,
        ],
        'invokables' => [
            Common\Helper\RouteResultHelper::class => Common\Helper\RouteResultHelper::class,
        ],
        'factories' => [
            'session' => Auth\SessionFactory::class,
            'redis' => Common\RedisConnectionFactory::class,

            Application::class => ApplicationFactory::class,
        ],
    ],
];
