<?php
return [
    'routes' => [
        [
            'name' => 'media-get-id',
            'path' => '/media',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\HandleMediaUpload::class,
            ],
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'media-upload',
            'path' => '/media',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\HandleMediaUpload::class,
            ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'media-upload-existing',
            'path' => '/media/{mediaId:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\HandleMediaUpload::class,
            ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'media-persist-existing',
            'path' => '/media/{mediaId:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\PersistMediaFromRedis::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_result' => 'success', // messy
                ],
            ],
        ],
        [
            'name' => 'media-404',
            'path' => '/uploads/default/{mediaFile:[^/]+}',
            'middleware' => [
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'chat::media-loading',
                    'layout' => 'layout::none',
                ],
            ],
        ],
        [
            'name' => 'media-proxy-remote',
            'path' => '/proxy/{mediaUrl:.+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\ProxyMedia::class,
            ],
            'allowed_methods' => ['GET'],
        ],
    ],
];
