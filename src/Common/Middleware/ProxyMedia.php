<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Stream;

class ProxyMedia
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$mediaUrl = $request->getAttribute('mediaUrl', false)) {
            return $response->withStatus(500, 'Unable to load image.');
        }
        if (!$mediaUrl = base64_decode($mediaUrl)) {
            return $response->withStatus(500, 'Unable to load image..');
        }

        if (!$mediaContent = file_get_contents($mediaUrl)) {
            return $response->withStatus(500, 'Unable to load image...');
        }

        $contentType = false;

        foreach ($http_response_header as $header) {
            if (strpos($header, 'Content-Type: image/') !== false) {
                $contentType = explode(': ', $header)[1];
                break;
            }
        }

        if (!$contentType) {
            return $response->withStatus(500, 'Unable to load image....');
        }

        $body = new Stream('php://temp', 'wb+');
        $body->write($mediaContent);
        $body->rewind();

        return $response->withHeader('Content-Type', $contentType)
                        ->withBody($body);
    }
}

