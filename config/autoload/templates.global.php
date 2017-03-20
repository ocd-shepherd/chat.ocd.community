<?php

return [
    'dependencies' => [
        'invokables' => [
            'layout' => Zend\View\Model\ViewModel::class,
        ],
        'factories' => [
            'Zend\Expressive\FinalHandler' =>
                Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,

            Zend\Expressive\Template\TemplateRendererInterface::class =>
                Common\Hacks\ZendViewRendererFactory::class,

            Zend\View\HelperPluginManager::class =>
                Zend\Expressive\ZendView\HelperPluginManagerFactory::class,
        ],
    ],

    'templates' => [
        'layout' => 'layout/material',
        'map' => [
            'layout/default' => 'templates/layout/default.phtml',
            'layout/material' => 'templates/layout/material.phtml',
            'error/error'    => 'templates/error/error.phtml',
            'error/404'      => 'templates/error/404.phtml',
        ],
        'paths' => [
            'page'      => ['templates/page'],
            'chat'      => ['templates/chat'],
            'community' => ['templates/community'],
            'auth'      => ['templates/auth'],
            'layout'    => ['templates/layout'],
            'error'     => ['templates/error'],
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'ident' => Auth\ViewHelper\IdentityFactory::class,
            'csrfToken' => Common\ViewHelper\CsrfTokenFactory::class,
            'routeParam' => Common\ViewHelper\RouteParamFactory::class,
            'privateChat' => Chat\ViewHelper\PrivateChatFactory::class,
        ],
        // zend-servicemanager-style configuration for adding view helpers:
        // - 'aliases'
        // - 'invokables'
        // - 'factories'
        // - 'abstract_factories'
        // - etc.
    ],
];
