<?php

declare(strict_types=1);

namespace Petronetto;

use Petronetto\Middlewares\ErrorResponseGenerator;
use Petronetto\Middlewares\NotFoundMiddleware;
use Petronetto\Middlewares\RouteProcessor;
use Petronetto\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\ErrorHandler;

use Zend\Stratigility\MiddlewarePipe;
use function Zend\Stratigility\middleware;

class Application
{
    /** @var ContainerInterface */
    private $container;

    /** @var Config */
    private $config;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
        $this->config    = Config::getInstance();
        $this->container = $this->getContainer();
        $this->router    = new Router();
    }

    /**
     * Get the container instance
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $this->container = $this->makeContainer();
        }

        return $this->container;
    }

    /**
     * Emitt the response
     *
     * @return void
     */
    public function run()
    {
        $pipeline = new MiddlewarePipe();

        // setup error handling
        $pipeline->pipe(
            new ErrorHandler(
                function (): ResponseInterface {
                    return $this->container->get('response');
                },
                new ErrorResponseGenerator(
                    // $this->container->get('logger'),
                    $this->isProd()
                )
            )
        );

        // Putting middlewares in pipeline
        $middlewares = $this->config->get('middlewares');
        foreach ($middlewares as $middleware) {
            $pipeline->pipe(new $middleware());
        }

        $request = $this->container->get('request');

        // Getting requested route details
        $routes = $this->router->getRoutes($request);

        // Putting middlewares for the found route in pipeline
        foreach ($routes['middlewares'] as $middleware) {
            $pipeline->pipe(new $middleware());
        }

        // Processing route
        $pipeline->pipe(new RouteProcessor($this->container, $routes));

        // Any middleware was found in pipeline
        // so, an error will be returned
        $pipeline->pipe(new NotFoundMiddleware());

        $response = $pipeline->handle($request);

        $this->container->get('emitter')->emit($response);
    }

    /**
     * Make the DI container
     *
     * @return ContainerInterface
     */
    private function makeContainer(): ContainerInterface
    {
        // If env is prod we'll enable the compilarion
        if ($this->config->get('application.prod')) {
            return (new \DI\ContainerBuilder())
                ->enableCompilation($this->config->get('application.cachedir'))
                ->useAnnotations(false)
                ->addDefinitions($this->config->get('di'))
                ->build();
        }

        return (new \DI\ContainerBuilder())
                ->useAnnotations(false)
                ->addDefinitions($this->config->get('di'))
                ->build();
    }

    /**
     * Check if application is running in prod
     *
     * @return boolean
     */
    public function isProd(): bool
    {
        return (bool) $this->config->get('application.prod');
    }
}
