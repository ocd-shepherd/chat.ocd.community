<?php
use Zend\Expressive\Router;

return [
    'dependencies' => [
        'invokables' => [
            Router\RouterInterface::class => Router\FastRouteRouter::class,
        ],
    ],
    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => [
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'page::home',
                ],
            ],
        ],
        [
            'name' => 'community/list',
            'path' => '/communities',
            'middleware' => [
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Community\Query\FeaturedCommunities::class,
                    'view_key' => 'communities',
                    'view' => 'community::featured',
                ],
            ],
        ],
        [
            'name' => 'community-create-form',
            'path' => '/communities/create',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'community::create',
                ],
            ],
        ],
        [
            'name' => 'community-path-available',
            'path' => '/communities/check',
            'middleware' => [
                Common\Middleware\CqrsPayloadFromQuery::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Community\Query\CommunityPathAvailable::class
                ],
            ],
        ],
        [
            'name' => 'community-create',
            'path' => '/communities/create',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\ValidateCsrf::class,
                Common\Middleware\CqrsPayloadFromPost::class,
                Common\Middleware\ValidateCqrsPayload::class,
                Common\Middleware\GenerateNewUuid::class,
                Auth\Middleware\PopulateCurrentUserId::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\Redirect::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'input_validator' => Community\Validator\CreateCommunity::class,
                    'cqrs_target' => Community\Command\CreateCommunity::class,
                    'new_uuid_field' => 'communityId',
                    'redirect_route' => 'community-manage',
                    // set the route param 'channel' (of the channel route) to the 'path' post param value
                    'redirect_params' => 'community:path',
                ],
            ],
        ],
        [
            'name' => 'community-manage',
            'path' => '/c/{community:[^/]+}/manage',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,

            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'community::manage',
                ],
            ],

        ],
    ],
];
