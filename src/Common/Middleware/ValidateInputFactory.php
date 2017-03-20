<?php
namespace Common\Middleware;

use Interop\Container\ContainerInterface;

class ValidateInputFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ValidateInput($container->get('session'));
    }
}
