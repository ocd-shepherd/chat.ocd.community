<?php

namespace Common\Hacks;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper as BaseServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper as BaseUrlHelper;
use Zend\Expressive\Router\RouterInterface;
use Zend\View\HelperPluginManager;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\Expressive\ZendView\ZendViewRenderer;
use Zend\Expressive\ZendView\UrlHelper;
use Zend\Expressive\ZendView\Exception;

class ZendViewRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @returns ZendViewRenderer
     */
    public function __invoke(ContainerInterface $container)
    {
        $config   = $container->has('config') ? $container->get('config') : [];
        $config   = isset($config['templates']) ? $config['templates'] : [];

        // Configuration
        $resolver = new Resolver\AggregateResolver();
        $resolver->attach(
            new Resolver\TemplateMapResolver(isset($config['map']) ? $config['map'] : []),
            100
        );

        // Create the renderer
        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);

        // Inject helpers
        $this->injectHelpers($renderer, $container);

        // HACK
        $layout = $container->get('layout');
        $layout->setTemplate($config['layout']);

        // Inject renderer
        $view = new ZendViewRenderer($renderer, $layout);

        // Add template paths
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $view->addPath($path, $namespace);
            }
        }

        return $view;
    }

    /**
     * Inject helpers into the PhpRenderer instance.
     *
     * If a HelperPluginManager instance is present in the container, uses that;
     * otherwise, instantiates one.
     *
     * In each case, injects with the custom url/serverurl implementations.
     *
     * @param PhpRenderer $renderer
     * @param ContainerInterface $container
     */
    private function injectHelpers(PhpRenderer $renderer, ContainerInterface $container)
    {
        $helpers = $container->has(HelperPluginManager::class)
            ? $container->get(HelperPluginManager::class)
            : new HelperPluginManager($container);

        $helpers->setAlias('url', BaseUrlHelper::class);
        $helpers->setAlias('Url', BaseUrlHelper::class);
        $helpers->setFactory(BaseUrlHelper::class, function () use ($container) {
            if (! $container->has(BaseUrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    BaseUrlHelper::class
                ));
            }
            return new UrlHelper($container->get(BaseUrlHelper::class));
        });

        $helpers->setAlias('serverurl', BaseServerUrlHelper::class);
        $helpers->setAlias('serverUrl', BaseServerUrlHelper::class);
        $helpers->setAlias('ServerUrl', BaseServerUrlHelper::class);
        $helpers->setFactory(BaseServerUrlHelper::class, function () use ($container) {
            if (! $container->has(BaseServerUrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    BaseServerUrlHelper::class
                ));
            }
            return new ServerUrlHelper($container->get(BaseServerUrlHelper::class));
        });

        $renderer->setHelperPluginManager($helpers);
    }
}

