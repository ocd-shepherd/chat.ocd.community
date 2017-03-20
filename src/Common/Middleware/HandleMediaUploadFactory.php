<?php
namespace Common\Middleware;

use Interop\Container\ContainerInterface;
use Auth\AuthService;

class HandleMediaUploadFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new HandleMediaUpload(
            $container->get('redis'),
            $container->get(AuthService::class)
        );
    }
}


