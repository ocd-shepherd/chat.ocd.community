<?php
declare(strict_types=1);

namespace Common\Middleware;

use Interop\Container\ContainerInterface;
use Common\Helper\RouteResultHelper;

class InjectRouteResultFactory
{
    public function __invoke(ContainerInterface $container): InjectRouteResult
    {
        return new InjectRouteResult(
            $container->get(RouteResultHelper::class)
        );
    }
}

