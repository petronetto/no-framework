<?php

namespace Petronetto\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteProcessor implements MiddlewareInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var array */
    private $routes;

    /**
     * @param ContainerInterface $container
     * @param array $routes
     */
    public function __construct(ContainerInterface $container, array $routes)
    {
        $this->container = $container;
        $this->routes    = $routes;
    }

    /**
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routes['params']['request'] = $request;
        $routes['params']['handler'] = $handler;

        return $this->container->call(
            $this->routes['handler'],
            $this->routes['params']
        );
    }
}
