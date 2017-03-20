<?php
namespace Auth\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Auth\AuthService;

final class PopulateCurrentUserId
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $target  = $request->getAttribute('current_user_id_field', 'userId');
        $payload = $request->getAttribute('cqrs_payload', []);

        $payload[$target] = $this->authService->hasIdentity() ? $this->authService->getIdentity()->id : null;

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}
