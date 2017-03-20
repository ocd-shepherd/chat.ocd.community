<?php
namespace Common\ViewHelper;

use Interop\Container\ContainerInterface;
use Common\Helper\RouteResultHelper;
use Zend\View\HelperPluginManager;

class RouteParamFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RouteParam(
            $container->get(RouteResultHelper::class)
        );
    }
}
