<?php
declare(strict_types=1);

namespace Common\Middleware;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper;

class RedirectFactory
{
    public function __invoke(ContainerInterface $container): Redirect
    {
        return new Redirect(
            $container->get('session'),
            $container->get(Helper\UrlHelper::class)
        );
    }
}
