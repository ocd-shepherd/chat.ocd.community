<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use EvantSource\DomainDispatcher;
use EvantSource\MessageSerializer;
use Ramsey\Uuid\Uuid;
use Auth\AuthService;

class DispatchCqrsRequest
{
    private $dispatcher;

    private $serializer;

    public function __construct(
        DomainDispatcher $dispatcher,
        MessageSerializer $serializer
    )
    {
        $this->dispatcher = $dispatcher;
        $this->serializer = $serializer;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getMethod() === 'GET') return $this->get($request, $response, $next);
        if ($request->getMethod() === 'POST') return $this->post($request, $response, $next);
    }

    private function get(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $target     = $request->getAttribute('cqrs_target');
        $payload    = $request->getAttribute('cqrs_payload', []);
        $dispatchee = $this->serializer->unserialize($target, $payload);
        $result     = $this->dispatcher->dispatch($dispatchee);

        return $next($request->withAttribute('cqrs_result', $result), $response);
    }

    private function post(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $payload = $request->getAttribute('cqrs_payload', []);

        $target = $request->getAttribute('cqrs_target');

        if ($fields = $request->getAttribute('cqrs_ignore_fields')) {
            $fields = explode('|', $request->getAttribute('cqrs_ignore_fields'));
            foreach ($fields as $field) {
                unset($payload[$field]);
            }
        }

        $dispatchee = $this->serializer->unserialize($target, $payload);

        $result = $this->dispatcher->dispatch($dispatchee);

        return $next($request->withAttribute('cqrs_result', $result), $response);
    }
}
