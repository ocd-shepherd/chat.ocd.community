<?php
namespace Auth\Command;

use Interop\Container\ContainerInterface;
use EvantSource\AggregateRepository;
use Auth\Finder\UserFinder;

final class ChangePasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ChangePasswordHandler(
            $container->get(AggregateRepository::class)
        );
    }
}
