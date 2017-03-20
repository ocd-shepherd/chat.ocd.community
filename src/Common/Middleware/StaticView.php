<?php
namespace Common\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use EvantSource\DomainDispatcher;

final class StaticView
{
    /**
     * @var TemplateRendererInterface
     */
    private $templates;

    /**
     * @param TemplateRendererInterface $templates
     */
    public function __construct(TemplateRendererInterface $templates)
    {
        $this->templates = $templates;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $result = $request->getAttribute('cqrs_result', []);

        if ($viewKey = $request->getAttribute('view_key', false)) {
            $viewData = [$viewKey => $result];
        } else {
            $viewData = $result;
        }

        if ($layout = $request->getAttribute('layout')) {
            $viewData['layout'] = $layout;
        }

        return new HtmlResponse(
            $this->templates->render($request->getAttribute('view'), $viewData)
        );
    }
}
