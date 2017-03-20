<?php
/**
 * Authentication routes: username check, register, login, logout
 */
return [
    'routes' => [
        [
            'name'            => 'auth-register-form',
            'path'            => '/register',
            'middleware'      => Common\Middleware\StaticView::class,
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'auth::register',
                ],
            ],
        ],
        [
            'name' => 'auth-register',
            'path' => '/register',
            'middleware' => [
                Common\Middleware\ValidateCsrf::class,
                //Auth\Middleware\VerifyCaptcha::class,
                Common\Middleware\CqrsPayloadFromPost::class,
                Common\Middleware\ValidateCqrsPayload::class,
                Common\Middleware\GenerateNewUuid::class,
                Auth\Middleware\HashPasswordInput::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Auth\Middleware\Authenticate::class,
                Common\Middleware\Redirect::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'input_validator' => Auth\Validator\RegisterUser::class,
                    'cqrs_target' => Auth\Command\RegisterUser::class,
                    'new_uuid_field' => 'userId',
                    'redirect_route'  => 'community-chat',
                    'redirect_params' => 'community:ocd',
                    'cqrs_ignore_fields' => 'password',
                ],
            ],
        ],
        [
            'name' => 'auth-username-available',
            'path' => '/register/check',
            'middleware' => [
                Common\Middleware\CqrsPayloadFromQuery::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Auth\Query\UsernameAvailable::class
                ],
            ],
        ],
        [
            'name' => 'auth-login-form',
            'path' => '/login',
            'middleware' => Common\Middleware\StaticView::class,
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'auth::login',
                ],
            ],
        ],
        [
            'name' => 'auth-login',
            'path' => '/login',
            'middleware' => [
                Common\Middleware\ValidateCsrf::class,
                Auth\Middleware\Authenticate::class,
                Common\Middleware\Redirect::class,
            ],
            'options' => [
                'defaults' => [
                    'redirect_route'  => 'community-chat',
                    'redirect_params' => 'community:ocd',
                ],
            ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'auth-logout-page',
            'path' => '/logout',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'auth::logout',
                ],
            ],
        ],
        [
            'name' => 'auth-logout',
            'path' => '/logout',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\ValidateCsrf::class,
                Auth\Middleware\Deauthenticate::class,
                Common\Middleware\Redirect::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'redirect_route' => 'home',
                ],
            ],
        ],
        [
            'name' => 'auth-change-password-form',
            'path' => '/change-password',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'auth::change-password',
                ],
            ],
        ],
        [
            'name' => 'auth-change-password',
            'path' => '/change-password',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\ValidateCsrf::class,
                Common\Middleware\CqrsPayloadFromPost::class,
                Common\Middleware\ValidateCqrsPayload::class,
                Auth\Middleware\PopulateCurrentUserId::class,
                Auth\Middleware\HashPasswordInput::class,
                Auth\Middleware\Authenticate::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\Redirect::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'input_validator' => Auth\Validator\ChangePassword::class,
                    'cqrs_target' => Auth\Command\ChangePassword::class,
                    'cqrs_ignore_fields' => 'newPassword|password',
                    'password_field' => 'newPassword',
                    'password_hash_field' => 'newPasswordHash',
                    'redirect_route' => 'home',
                ],
            ],
        ],





        [
            'name' => 'avatar-form',
            'path' => '/avatar',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\StaticView::class,
            ],
            'allowed_methods' => ['GET'],
            'options' => [
                'defaults' => [
                    'view' => 'auth::avatar',
                ],
            ],
        ],

        [
            'name' => 'avatar-set',
            'path' => '/avatar',
            'middleware' => [
                Auth\Middleware\AssertIsAuthenticated::class,
                Common\Middleware\PersistMediaFromRedis::class,
                Common\Middleware\CqrsPayloadFromPost::class,
                Auth\Middleware\PopulateCurrentUserId::class,
                Common\Middleware\DispatchCqrsRequest::class,
                Common\Middleware\ReturnJsonResponse::class,
            ],
            'allowed_methods' => ['POST'],
            'options' => [
                'defaults' => [
                    'cqrs_target' => Chat\Command\ChangeAvatar::class,
                    'media_dest_path' => 'avatars',
                    'cqrs_ignore_fields' => 'mediaId',
                    'media_path_cqrs_key' => 'mediaPath',
                ],
            ],
        ],

    ],
];
