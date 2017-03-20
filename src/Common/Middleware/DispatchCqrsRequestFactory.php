<?php
declare(strict_types=1);

namespace Common\Middleware;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use EvantSource\DomainDispatcher;
use EvantSource\MessageSerializer;
use Auth\AuthService;

class DispatchCqrsRequestFactory
{
    public function __invoke(ContainerInterface $container): DispatchCqrsRequest
    {
        return new DispatchCqrsRequest(
            $container->get(DomainDispatcher::class),
            new MessageSerializer,
            $container->get(AuthService::class)
        );
    }
}

