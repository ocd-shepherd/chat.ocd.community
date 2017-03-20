<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Aura\Session\Session;

final class ValidateCsrf
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $token = $this->session->getCsrfToken();

        if (! $token->isValid($_POST['__csrf_value'])) {
            throw new \Exception('CSRF failure.');
        }

        return $next($request, $response);
    }
}

