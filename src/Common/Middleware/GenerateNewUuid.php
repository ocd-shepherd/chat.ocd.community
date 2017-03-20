<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class GenerateNewUuid
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $target  = $request->getAttribute('new_uuid_field', 'id');
        $payload = $request->getAttribute('cqrs_payload', []);

        $payload[$target] = Uuid::uuid4()->toString();

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}
