<?php
namespace Community\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Router\RouteResult;
use Community\Finder\CommunityFinder;

final class PopulateCommunityIdFromPath
{
    private $communityFinder;

    public function __construct(CommunityFinder $communityFinder)
    {
        $this->communityFinder = $communityFinder;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $matchedParams = $request->getAttribute(RouteResult::class, false)
                                 ->getMatchedParams();

        $target  = $request->getAttribute('community_id_field', 'communityId');
        $payload = $request->getAttribute('cqrs_payload', []);

        $payload[$target] = $this->communityFinder->findIdByPath($matchedParams['community']);

        return $next($request->withAttribute('cqrs_payload', $payload), $response);
    }
}

