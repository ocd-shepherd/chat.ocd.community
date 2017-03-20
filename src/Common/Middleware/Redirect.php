<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\RouteResult;
use Aura\Session\Session;

// TODO: This whole thing is pretty hacky and needs cleaned up.
final class Redirect
{
    private $session;

    private $urlHelper;

    public function __construct(Session $session, UrlHelper $urlHelper)
    {
        $this->session = $session;
        $this->urlHelper = $urlHelper;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $code = $request->getAttribute('redirect_code', '302');

        if ($routeName = $request->getAttribute('redirect_route')) {

            $routeParams = [];
            $payload = $request->getParsedBody();
            $redirectParams = explode('|', $request->getAttribute('redirect_params', ''));

            foreach ($redirectParams as $param) {
                list($routeParam, $postParam) = explode(':', $param);
                $routeParams[$routeParam] = $payload[$postParam] ?? $postParam;
            }

            if ($request->getAttribute('redirect_with_current_route_params', false)) {

                $matchedParams = $request->getAttribute(RouteResult::class)
                                         ->getMatchedParams();

                foreach ($matchedParams as $key => $value) {
                    $routeParams[$key] = $value;
                }

            }

            $path = $this->urlHelper->__invoke($routeName, $routeParams);

        } else {

            $path = $request->getAttribute('redirect_path', '/');

        }

        return $response
            ->withStatus($code)
            ->withHeader('Location', $path);
    }
}
