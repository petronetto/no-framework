<?php

declare(strict_types=1);

namespace Petronetto;

use Petronetto\Exceptions\ExceptionHandler;
use Petronetto\Exceptions\ExceptionLogger;
use Petronetto\Middlewares\RouteProcessor;
use Petronetto\Routing\Router;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\MiddlewarePipe;

class Application
{
    /** @var ContainerInterface */
    private $container;

    /** @var Router */
    private $router;

    /** @var Config */
    private $config;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(string $routes = null)
    {
        $this->config    = Config::getInstance();
        $this->container = $this->makeContainer();
        $this->router    = new Router($routes);
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
     * Run the application and emitt the response
     *
     * @return void
     */
    public function run()
    {
        try {
            $pipeline = new MiddlewarePipe();

            // Putting middlewares in pipeline
            $middlewares = $this->config->get('middlewares', []);
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

            $response = $pipeline->handle($request);
        } catch (\Throwable $t) {
            $logger   = $this->container->get('logger');
            $request  = $this->container->get('request');
            $response = ExceptionHandler::handle($t, $request, $this->isProd());
            ExceptionLogger::handle($t, $logger, $request);
        }

        $this->container->get('emitter')->emit($response);
    }

    /**
     * Check if application is running in prod
     *
     * @return boolean
     */
    public function isProd(): bool
    {
        return (bool) $this->config->get('app.prod', true);
    }

    /**
     * Make the DI container
     *
     * @return ContainerInterface
     */
    private function makeContainer(): ContainerInterface
    {
        // If env is prod we'll enable the compilarion
        if ($this->isProd()) {
            return (new \DI\ContainerBuilder())
                ->enableCompilation($this->config->get('app.cachedir'))
                ->useAnnotations(false)
                ->addDefinitions($this->config->get('di'))
                ->build();
        }

        return (new \DI\ContainerBuilder())
                ->useAnnotations(false)
                ->addDefinitions($this->config->get('di'))
                ->build();
    }
}
