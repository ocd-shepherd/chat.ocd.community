<?php
return [
    'dependencies' => [
        'factories' => [
            EvantSource\DomainDispatcher::class => EvantSource\Container\DomainDispatcherFactory::class,
            EvantSource\AggregateRepository::class => EvantSource\Container\AggregateRepositoryFactory::class,
            EvantSource\CqrsMiddleware::class => EvantSource\Container\CqrsMiddlewareFactory::class,
        ],
    ],

    'evantsource' => [
        Auth\Query\UsernameAvailable::class => [
            Auth\Query\UsernameAvailableHandler::class,
        ],

        Auth\Command\RegisterUser::class => [
            Auth\Command\RegisterUserHandler::class,
        ],

        Auth\Event\UserRegistered::class => [
            Auth\Listener\UserProjector::class,
        ],

        Auth\Command\ChangePassword::class => [
            Auth\Command\ChangePasswordHandler::class,
        ],

        Auth\Event\PasswordChanged::class => [
            Auth\Listener\UserProjector::class,
        ],

        Community\Query\FeaturedCommunities::class => [
            Community\Query\FeaturedCommunitiesHandler::class,
        ],

        Community\Query\CommunityPathAvailable::class => [
            Community\Query\CommunityPathAvailableHandler::class,
        ],

        Community\Command\CreateCommunity::class => [
            Community\Command\CreateCommunityHandler::class,
        ],

        Community\Event\CommunityCreated::class => [
            Community\Listener\CommunityProjector::class,
        ],

        Community\Query\UsersInCommunity::class => [
            Community\Query\UsersInCommunityHandler::class,
        ],

        Chat\Query\RoomMessages::class => [
            Chat\Query\RoomMessagesHandler::class,
        ],

        Chat\Command\SendMessage::class => [
            Chat\Listener\MessageProjector::class,
        ],

        Chat\Command\ChangeAvatar::class => [
            Auth\Listener\UserProjector::class,
        ],
    ],
];
