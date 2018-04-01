<?php

namespace Petronetto\Routing;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased as GroupCountBasedDispatcher;
use FastRoute\RouteParser\Std;
use Petronetto\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    const ROUTES_DIR = __DIR__ . '/../../routes/api.php';

    private $collector;

    public function __construct()
    {
        $this->collector = $this->getRouteCollector();
        $this->registerRoutes();
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
     * @throws NotFoundHttpException
     */
    public function getRoutes(ServerRequestInterface $request): array
    {
        $dispatcher = $this->getDispatcher();

        $routes = $dispatcher->dispatch(
            $request->getMethod(),
            $this->normalizePath($request)
        );

        if ($routes[0] === Dispatcher::NOT_FOUND) {
            // throw new \Exception("Vamo entender essa porra...");
            throw new NotFoundHttpException('Route not found');
        }

        if ($routes[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new NotFoundHttpException('Route not found');
        }

        // Getting middlewares for route
        $middlewares = $this->collector->getRouteMiddlewares($routes[1]);

        return [
            'handler'     => $routes[1],
            'params'      => $routes[2],
            'middlewares' => $middlewares,
        ];
    }

    /**
     * @param  ServerRequestInterface $request
     * @return void
     */
    public function normalizePath(ServerRequestInterface $request)
    {
        $path = preg_replace('/\/+/', '/', trim($request->getUri()->getPath(), '/'));

        return "/{$path}/";
    }

    /**
     * Register the routes
     */
    public function registerRoutes()
    {
        $this->collector->group('', function ($router) {
            require self::ROUTES_DIR;
        });
    }
}
