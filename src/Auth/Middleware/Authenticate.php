<?php
namespace Auth\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Router\RouteResult;
use Auth\AuthService;

final class Authenticate
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (array_key_exists('username', $request->getParsedBody())) {

            $username = $request->getParsedBody()['username'];

        } else if ($this->authService->hasIdentity()) {

            $username = $this->authService->getIdentity()->username;

        }

        $password = $request->getParsedBody()['password'];

        if (!$this->authService->authenticate($username, $password)) {

            throw new \Exception('Authentication failed.');

        }

        return $next($request, $response);
    }
}
