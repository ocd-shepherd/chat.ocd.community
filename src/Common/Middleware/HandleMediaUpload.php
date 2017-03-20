<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Predis\Client;
use Zend\Diactoros\Response\JsonResponse;
use Auth\AuthService;
use Zend\Expressive\Router\RouteResult;

class HandleMediaUpload
{
    private $redis;

    private $authService;

    public function __construct(Client $redis, AuthService $authService)
    {
        $this->redis = $redis;
        $this->authService = $authService;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $contentType = $request->getHeader('Content-Type')[0];
        //$contentType = $_GET['t'];
        $userId      = $this->authService->getIdentity()->id;
        $reqMediaId  = $request->getAttribute(RouteResult::class)->getMatchedParams()['mediaId'] ?? false;
        $mediaId     = $reqMediaId ?: Uuid::uuid4()->toString();
        $rKey        = "media:{$userId}:{$mediaId}";

        if ($request->getMethod() === 'GET') {
            $this->redis->hset($rKey, 'contentType', $contentType);
            $this->redis->expire($rKey, 3600);
            return new JsonResponse(['mediaId' => $mediaId, 'contentType' => $contentType]);
        }

        if ($request->getMethod() === 'POST') {

            // If it's a client-supplied media ID, make sure it's valid.
            if ($reqMediaId) {

                if (!$this->redis->exists($rKey)) {
                    return new JsonResponse(['error' => 'Invalid media ID']);
                }

                if ($contentType != $this->redis->hget($rKey, 'contentType')) {
                    return new JsonResponse(['error' => 'Invalid content type.']);
                }

            }

            $data = $request->getBody()->getContents();
            $this->redis->hset($rKey, 'contentType', $contentType);
            $this->redis->hset($rKey, 'data', $data);
            $this->redis->expire($rKey, 60);
        }

        return new JsonResponse(['mediaId' => $mediaId]);
    }
}

