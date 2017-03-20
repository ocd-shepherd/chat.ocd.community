<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CqrsPayloadFromQuery
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $payload = $request->getQueryParams() + $request->getAttribute('cqrs_payload', []);

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}
