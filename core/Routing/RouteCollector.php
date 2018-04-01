<?php

namespace Petronetto\Routing;

use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use SplQueue;

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
     * Constructs a route collector.
     *
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
     * @param string $handler
     * @return array
     */
    public function getRouteMiddlewares($handler): array
    {
        //
        if (array_key_exists($handler, $this->middlewares)) {
            return $this->middlewares[$handler];
        }

        return [];
    }

    /**
     * Create a route group with a common prefix.
     *
     * All routes created in the passed callback will have the given group prefix prepended.
     *
     * @param string   $prefix
     * @param callable $callback
     */
    public function group($prefix, callable $callback)
    {
        $previousGroupPrefix      = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $callback($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
    }

    /**
     * Adds a GET route to the collection
     *
     * This is simply an alias of $this->addRoute('GET', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     */
    public function get($route, $handler, array $middlewares = [])
    {
        $this->addRoute('GET', $route, $handler, $middlewares);
    }

    /**
     * Adds a POST route to the collection
     *
     * This is simply an alias of $this->addRoute('POST', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     */
    public function post($route, $handler, array $middlewares = [])
    {
        $this->addRoute('POST', $route, $handler, $middlewares);
    }

    /**
     * Adds a PUT route to the collection
     *
     * This is simply an alias of $this->addRoute('PUT', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     */
    public function put($route, $handler, array $middlewares = [])
    {
        $this->addRoute('PUT', $route, $handler, $middlewares);
    }

    /**
     * Adds a DELETE route to the collection
     *
     * This is simply an alias of $this->addRoute('DELETE', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     */
    public function delete($route, $handler, array $middlewares = [])
    {
        $this->addRoute('DELETE', $route, $handler, $middlewares);
    }

    /**
     * Adds a PATCH route to the collection
     *
     * This is simply an alias of $this->addRoute('PATCH', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     */
    public function patch($route, $handler, array $middlewares = [])
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
     * Adds a route to the collection.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string   $httpMethod
     * @param string   $route
     * @param mixed    $handler
     */
    private function addRoute(string $httpMethod, string $route, $handler, array $middlewares = [])
    {
        $route = $this->normalizeRoute(
            $this->currentGroupPrefix . $route
        );

        // Puting route middlewares in a key value array
        if ($middlewares) {
            $routeMiddlewares = [];
            foreach ($middlewares as $m) {
                $routeMiddlewares[$handler][] = $m;
            }

            $this->middlewares = $routeMiddlewares;
        }

        $routeDatas = $this->routeParser->parse($route);
        foreach ($routeDatas as $routeData) {
            $this->dataGenerator->addRoute($httpMethod, $routeData, $handler);
        }
    }

    /**
     * Clean tailing slash form route.
     *
     * @param string $route
     * @return string
     */
    private function normalizeRoute(string $route): string
    {
        $route = preg_replace('/\/+/', '/', trim($route, '/'));

        return "/{$route}/";
    }
}
