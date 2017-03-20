<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Session\Session;
use Respect\Validation\Exceptions\ValidationException;

final class ValidateInput
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

        try {
            $validator->assert($request->getParsedBody());
        } catch (ValidationException $e) {
            var_dump($e->getMessages());die();

        }

        return $next($request, $response);
    }
}


