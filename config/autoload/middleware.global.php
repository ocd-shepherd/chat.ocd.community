<?php
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'middleware_pipeline' => [
        'route' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                Helper\UrlHelperMiddleware::class,
                Common\Middleware\InjectRouteResult::class,
            ],
            'priority' => 100,
        ],
        'dispatching' => [
            'middleware' => [
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority' => 1
        ],
    ],
];
