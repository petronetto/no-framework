<?php

namespace Petronetto\Routing;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    const ROUTES_DIR = __DIR__ . '/../../routes/api.php';

    private $collector;

    public function __construct()
    {
        $this->collector = $this->getRouteCollector();
        $this->initRoutes();
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

        // Cleaning extra slashes
        $path = preg_replace('/\/+/', '/', trim($request->getUri()->getPath(), '/'));

        $routes = $dispatcher->dispatch(
            $request->getMethod(),
            "/{$path}/"
        );

        if ($routes[0] === Dispatcher::NOT_FOUND) {
            // TODO: Error Handlers 404
            die('404');
        }

        if ($routes[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            // TODO: Error Handlers 405
            die('405');
        }

        // TODO: Check route middleware
        $middlewares = $this->collector->getRouteMiddlewares($routes[1]);

        return [
            'handler'     => $routes[1],
            'params'      => $routes[2],
            'middlewares' => $middlewares,
        ];
    }

    public function initRoutes()
    {
        $this->collector->group('', function ($router) {
            require self::ROUTES_DIR;
        });
    }
}
