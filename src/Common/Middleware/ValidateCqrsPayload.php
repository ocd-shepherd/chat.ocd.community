<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Session\Session;
use Respect\Validation\Exceptions\ValidationException;

final class ValidateCqrsPayload
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$validator = $request->getAttribute('input_validator')) {
            throw new \Exception('You need to set an input_validator for this route');
        }

        $validator = $validator::{'validator'}();
        $payload   = $request->getAttribute('cqrs_payload', []);

        $validator->assert($payload);

        return $next($request, $response);
    }
}
