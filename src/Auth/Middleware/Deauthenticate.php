<?php
namespace Auth\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Router\RouteResult;
use Auth\AuthService;

final class Deauthenticate
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->authService->clearIdentity();

        return $next($request, $response);
    }
}
