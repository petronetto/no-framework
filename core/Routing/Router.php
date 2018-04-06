<?php

namespace Petronetto\Routing;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**  @var string */
    private const ROUTES_FILE = __DIR__ . '/../../routes/api.php';

    /**  @var RouteCollector */
    private $collector;

    /**
     * @param string $routes
     */
    public function __construct(string $routes = null)
    {
        if (!$routes) {
            $routes = self::ROUTES_FILE;
        }

        $this->collector = $this->getRouteCollector();
        $this->registerRoutes($routes);
    }

    /**
     * Get the dispatcher
     *
     * @return RouteCollector
     */
    private function getRouteCollector(): RouteCollector
    {
        if ($this->collector === null) {
            $this->collector = new RouteCollector(new Std(), new DataGenerator());
        }

        return $this->collector;
    }

    /**
     * Get the dispatcher
     *
     * @return Dispatcher
     */
    private function getDispatcher(): Dispatcher
    {
        return new GroupCountBasedDispatcher($this->collector->getData());
    }

    /**
     * @param  ServerRequestInterface $request
     * @return array
     */
    public function getRoutes(ServerRequestInterface $request): array
    {
        $dispatcher = $this->getDispatcher();

        $routes = $dispatcher->dispatch(
            $request->getMethod(),
            $this->normalizePath($request)
        );

        if ($routes[0] === Dispatcher::NOT_FOUND) {
            return [
                'result'      => Dispatcher::NOT_FOUND,
                'handler'     => null,
                'params'      => [],
                'middlewares' => [],
            ];
        }

        if ($routes[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return [
                'result'      => Dispatcher::METHOD_NOT_ALLOWED,
                'handler'     => null,
                'params'      => [],
                'middlewares' => [],
            ];
        }

        // Getting middlewares for route
        $middlewares = $this->collector->getRouteMiddlewares($routes[1]);

        return [
            'result'      => Dispatcher::FOUND,
            'handler'     => $routes[1],
            'params'      => $routes[2],
            'middlewares' => $middlewares,
        ];
    }

    /**
     * @param  ServerRequestInterface $request
     * @return string
     */
    public function normalizePath(ServerRequestInterface $request): string
    {
        $path = preg_replace('/\/+/', '/', trim($request->getUri()->getPath(), '/'));

        return "/{$path}/";
    }

    /**
     * @param string $routes
     * @return void
     */
    public function registerRoutes(string $routes)
    {
        $this->collector->group('', function ($router) use ($routes) {
            require $routes;
        });
    }
}
