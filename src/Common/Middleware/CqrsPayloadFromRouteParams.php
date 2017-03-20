<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Router\RouteResult;

final class CqrsPayloadFromRouteParams
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $params = $request->getAttribute(RouteResult::class)->getMatchedParams();
        unset($params['cqrs_target']);
        unset($params['new_uuid_field']);

        $payload = $params + $request->getAttribute('cqrs_payload', []);

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}
