<?php

namespace Petronetto\Middlewares;

use FastRoute\Dispatcher;
use Petronetto\Exceptions\NotAllowedHttpException;
use Petronetto\Exceptions\NotFoundHttpException;
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
     * @param array              $routes
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
        if ($this->routes['result'] === Dispatcher::NOT_FOUND) {
            throw new NotFoundHttpException($request);
        }

        if ($this->routes['result'] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new NotAllowedHttpException($request);
        }

        return $this->container->call(
            $this->routes['handler'],
            $this->routes['params']
        );
    }
}
