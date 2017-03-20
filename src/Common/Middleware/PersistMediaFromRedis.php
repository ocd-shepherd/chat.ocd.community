<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Predis\Client;
use Zend\Diactoros\Response\JsonResponse;
use Auth\AuthService;
use Zend\Expressive\Router\RouteResult;

class PersistMediaFromRedis
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
        $userId     = $this->authService->getIdentity()->id;
        $key        = $request->getAttribute('media_id_key', 'mediaId');
        $destFolder = $request->getAttribute('media_dest_path', 'default');

        if ($request->getMethod() === 'POST') {
            $mediaId  = $request->getParsedBody()[$key] ?? false;
        }
        if ($request->getMethod() === 'GET') {
            $mediaId  = $request->getAttribute(RouteResult::class)->getMatchedParams()[$key] ?? false;
        }

        $rKey       = "media:{$userId}:{$mediaId}";
        $media      = $this->redis->hgetall($rKey);

        if(!$media) {
            throw new \Exception('Invalid media');
        }

        $ext        =
            ($media['contentType'] == 'image/jpeg' ? 'jpg' : false) ?:
            ($media['contentType'] == 'image/png'  ? 'png' : false) ?:
            ($media['contentType'] == 'image/gif'  ? 'gif' : false) ?:
            'wtf';
        $mediaPath  = "public/uploads/{$destFolder}/{$mediaId}.{$ext}";

        file_put_contents($mediaPath, $media['data']);

        $this->redis->del($rKey);


        if ($key = $request->getAttribute('media_path_cqrs_key', false)) {
            $payload = $request->getAttribute('cqrs_payload', []);
            $payload[$key] = str_replace('public','',$mediaPath);
            $request = $request->withAttribute('cqrs_payload', $payload);
        }

        return $next($request, $response);
    }
}
