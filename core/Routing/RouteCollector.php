<?php

namespace Petronetto\Routing;

use FastRoute\DataGenerator;
use FastRoute\RouteParser;

class RouteCollector
{
    /** @var RouteParser */
    private $routeParser;

    /** @var DataGenerator */
    private $dataGenerator;

    /** @var string */
    private $currentGroupPrefix;

    /** @var array */
    private $middlewares;

    /**
     * @param RouteParser   $routeParser
     * @param DataGenerator $dataGenerator
     */
    public function __construct(RouteParser $routeParser, DataGenerator $dataGenerator)
    {
        $this->routeParser        = $routeParser;
        $this->dataGenerator      = $dataGenerator;
        $this->currentGroupPrefix = '';
        $this->middlewares        = [];
    }

    /**
     * @param  string $handler
     * @return array
     */
    public function getRouteMiddlewares($handler): array
    {
        if (array_key_exists($handler, $this->middlewares)) {
            return $this->middlewares[$handler];
        }

        return [];
    }

    /**
     * @param string   $prefix
     * @param callable $callback
     */
    public function group(string $prefix, callable $callback): void
    {
        $previousGroupPrefix      = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $callback($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
    }

    /**
     * @param  string $route
     * @param  string $handler
     * @param  array  $middlewares
     * @return void
     */
    public function get(string $route, string $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $route, $handler, $middlewares);
    }

    /**
     * @param  string $route
     * @param  string $handler
     * @param  array  $middlewares
     * @return void
     */
    public function post(string $route, string $handler, array $middlewares = [])
    {
        $this->addRoute('POST', $route, $handler, $middlewares);
    }

    /**
     * @param  string $route
     * @param  string $handler
     * @param  array  $middlewares
     * @return void
     */
    public function put(string $route, string $handler, array $middlewares = [])
    {
        $this->addRoute('PUT', $route, $handler, $middlewares);
    }

    /**
     * @param  string $route
     * @param  string $handler
     * @param  array  $middlewares
     * @return void
     */
    public function delete(string $route, string $handler, array $middlewares = [])
    {
        $this->addRoute('DELETE', $route, $handler, $middlewares);
    }

    /**
     * @param  string $route
     * @param  string $handler
     * @param  array  $middlewares
     * @return void
     */
    public function patch(string $route, string $handler, array $middlewares = [])
    {
        $this->addRoute('PATCH', $route, $handler, $middlewares);
    }

    /**
     * Returns the collected route data, as provided by the data generator.
     *
     * @return array
     */
    public function getData()
    {
        return $this->dataGenerator->getData();
    }

    /**
     * @param string $httpMethod
     * @param string $route
     * @param mixed  $handler
     */
    private function addRoute(string $httpMethod, string $route, $handler, array $middlewares = [])
    {
        $route = $this->normalizeRoute(
            $this->currentGroupPrefix . $route
        );

        // Puting route middlewares in a key value array
        if ($middlewares) {
            foreach ($middlewares as $m) {
                $this->middlewares[$handler][] = $m;
            }
        }

        $routeDatas = $this->routeParser->parse($route);
        foreach ($routeDatas as $routeData) {
            $this->dataGenerator->addRoute($httpMethod, $routeData, $handler);
        }
    }

    /**
     * Clean tailing slash form route.
     *
     * @param  string $route
     * @return string
     */
    private function normalizeRoute(string $route): string
    {
        $route = preg_replace('/\/+/', '/', trim($route, '/'));

        return "/{$route}/";
    }
}
