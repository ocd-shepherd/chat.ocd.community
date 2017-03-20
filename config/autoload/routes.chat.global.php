<?php
return [
    'routes' => [
        [
            'name' => 'community-users',
            'path' => '/c/{community:[^/]+}/users',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\CqrsPayloadFromQuery::class,
                Community\Middleware\PopulateCommunityIdFromPath::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\StaticView::class,

            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Community\Query\UsersInCommunity::class,
                    'view' => 'chat::users',
                    'view_key' => 'users',
                ],
            ],

        ],
        [
            'name' => 'community-chat',
            'path' => '/c/{community:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,

            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'chat::index',
                ],
            ],

        ],

        [
            'name' => 'private-chat',
            'path' => '/p/{username:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,

            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'chat::index',
                ],
            ],

        ],

        [
            'name' => 'chat-messages',
            'path' => '/messages/{roomId:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\CqrsPayloadFromRouteParams::class,
                Common\Middleware\CqrsPayloadFromQuery::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Chat\Query\RoomMessages::class,
                ],
            ],

        ],
        [
            'name' => 'chat-send-message',
            'path' => '/messages/{roomId:[^/]+}',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\CqrsPayloadFromRouteParams::class,
                Common\Middleware\CqrsPayloadFromPost::class,
                Common\Middleware\GenerateNewUuid::class,
                Auth\Middleware\PopulateCurrentUserId::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'new_uuid_field' => 'messageId',
                    'cqrs_target' => Chat\Command\SendMessage::class,
                ],
            ],

        ],
    ],
];
