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

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
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
     * Run the application and emitt the response
     *
     * @return void
     */
    public function run()
    {
        try {
            $pipeline = new MiddlewarePipe();

            // Putting middlewares in pipeline
            $middlewares = Config::get('middlewares');
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
        return (bool) Config::get('application.prod');
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
                ->enableCompilation(Config::get('application.cachedir'))
                ->useAnnotations(false)
                ->addDefinitions(Config::get('di'))
                ->build();
        }

        return (new \DI\ContainerBuilder())
                ->useAnnotations(false)
                ->addDefinitions(Config::get('di'))
                ->build();
    }
}
