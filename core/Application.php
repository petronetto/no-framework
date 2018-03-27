<?php

declare(strict_types=1);

namespace Petronetto;

use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher\GroupCountBased;

class Application
{
    /** @var ContainerInterface */
    private $container;

    /** @var Config */
    private $config;

    /** @var Dispatcher */
    private $dispatcher;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
        $this->bootErrorHandler();
        $this->config            = Config::getInstance();
        $this->dispatcher        = $this->dispatcher();
        $this->container         = $this->getContainer();
    }

    /**
     * Handler with requests.
     *
     * @param  ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function handle(ServerRequestInterface $request): ResponseInterface
    {
        $router = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($router[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new \UnexpectedValueException('Method not allowed');
            case Dispatcher::NOT_FOUND:
                throw new \OutOfRangeException('Route not found');
            case Dispatcher::FOUND:
                return $this->container->call($router[1], $router[2]);
        }
    }

    /**
     * Get the dispatcher
     *
     * @return Dispatcher
     */
    private function dispatcher()
    {
        $routeCollector = new RouteCollector(new Std(), new DataGenerator());

        $routes = $this->normalizeRouteParams(
            $this->config->get('routes')
        );

        $routeDefinitionCallback = function (RouteCollector $router) use ($routes) {
            foreach ($routes as $route) {
                $router->addRoute($route['methods'], $route['path'], $route['handler']);
            }
        };

        $routeDefinitionCallback($routeCollector);

        return new GroupCountBased($routeCollector->getData());
    }

    public function normalizeRouteParams(array $routes): array
    {
        foreach ($routes as $key => $route) {
            if (!isset($route['methods'])
                || !isset($route['handler'])
                || !isset($route['path'])) {
                // TODO: Create a dedicated Exception
                throw new \Exception('Invalid Route Parameters', 400);
            }

            if (!is_array($route['methods'])) {
                $route['methods'] = [$route['methods']];
            }

            if (!isset($route['middlewares'])) {
                $route['middlewares'] = [];
            }

            if (!is_array($route['middlewares'])) {
                $route['middlewares'] = [$route['middlewares']];
            }

            $routes[$key] = $route;
        }

        return $routes;
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
        $response = $this->handle(
            $this->container->get('request')
        );

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
     * Error Handlers
     *
     * @return void
     */
    private function bootErrorHandler(): void
    {
        (new \Whoops\Run())
            ->pushHandler(new \Whoops\Handler\JsonResponseHandler())
            ->pushHandler(new \Whoops\Handler\CallbackHandler(function ($exception) {
                $logger = container()->get(LoggerInterface::class);
                $logger->critical(
                    $exception->getMessage(),
                    ['exception' => $exception->__toString()]
                );
            }))
            ->register();
    }
}
