<?php
namespace Auth\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Auth\Model\PasswordHash;

class HashPasswordInput
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $passwordField     = $request->getAttribute('password_field', 'password');
        $passwordHashField = $request->getAttribute('password_hash_field', 'passwordHash');

        $payload = $request->getAttribute('cqrs_payload', []);

        $hash = PasswordHash::fromPlainText($payload[$passwordField])->toString();

        $payload[$passwordHashField] = $hash;

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}
