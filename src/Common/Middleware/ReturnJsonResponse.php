<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Zend\Diactoros\Response\JsonResponse;

final class ReturnJsonResponse
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        return new JsonResponse($request->getAttribute('cqrs_result'));
    }
}
