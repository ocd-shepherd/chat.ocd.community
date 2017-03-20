<?php
namespace Auth;

use Aura\Session\Session as AuraSession;
use Aura\Session\SessionFactory as AuraSessionFactory;
use Interop\Container\ContainerInterface;

class SessionFactory
{
    public function __invoke() : AuraSession
    {
        $factory = new AuraSessionFactory;
        $session = $factory->newInstance($_COOKIE);
        $session->setCookieParams(array('lifetime' => 86400 * 180)); // 6 month cookie

        return $session;
    }
}
