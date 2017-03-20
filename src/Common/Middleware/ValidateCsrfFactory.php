<?php
namespace Common\Middleware;

use Interop\Container\ContainerInterface;

class ValidateCsrfFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ValidateCsrf($container->get('session'));
    }
}
