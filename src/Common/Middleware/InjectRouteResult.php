<?php
namespace Common\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Common\Helper\RouteResultHelper;

class InjectRouteResult
{
    private $resultHelper;

    public function __construct(RouteResultHelper $resultHelper)
    {
        $this->resultHelper = $resultHelper;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $result = $request->getAttribute(RouteResult::class, false);

        $this->resultHelper->setRouteResult($result);

        return $next($request, $response);
    }
}
