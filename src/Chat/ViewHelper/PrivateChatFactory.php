<?php
namespace Chat\ViewHelper;

use Interop\Container\ContainerInterface;
use Auth\AuthService;
use Auth\Finder\UserFinder;

class PrivateChatFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new PrivateChat(
            $container->get(AuthService::class),
            $container->get(UserFinder::class)
        );
    }
}
