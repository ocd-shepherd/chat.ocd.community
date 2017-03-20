<?php
namespace Auth\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Auth\AuthService;

final class AssertIsAuthenticated
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$this->authService->hasIdentity()) {
            throw new \Exception('Authenticate required.');
        }

        return $next($request, $response);
    }
}
