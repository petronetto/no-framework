<?php

declare(strict_types=1);

namespace Petronetto;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use HelloFresh\Middlewares\CorsMiddleware;
use HelloFresh\Middlewares\ResponseTime;
use Petronetto\Middlewares\ErrorResponseGenerator;
use Petronetto\Middlewares\NotFoundMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Stratigility\MiddlewarePipe;
use function Zend\Stratigility\middleware;

class Router
{
    /** @var ContainerInterface */
    private $request;

    /** @var Config */
    private $response;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        // $this->request
        // $this->response        = $this->dispatcher();
        $this->response = Config::getInstance();
    }

    /**
     * Get the dispatcher
     *
     * @return Dispatcher
     */
    private function dispatcher(): Dispatcher
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

    /**
     * Normalize routes.
     *
     * @param  array $routes
     * @return array
     */
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

            if (count($route['middlewares'])) {
                $this->routeMiddlawares[$route['handler']] = $route['middlewares'];
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
        $pipeline = new MiddlewarePipe();

        // setup error handling
        $pipeline->pipe(
            new ErrorHandler(
                function (): ResponseInterface {
                    return $this->container->get('response');
                },
                new ErrorResponseGenerator(true)
            )
        );

        $pipeline->pipe(new ResponseTime());
        $pipeline->pipe(new CorsMiddleware());

        // Routes
        $pipeline->pipe(middleware($this->processRoutes()));

        $pipeline->pipe(new NotFoundMiddleware());

        $response = $pipeline->handle(
            $this->container->get('request')
        );

        $this->container->get('emitter')->emit($response);
    }

    /**
     * Processe routes and return a callable.
     *
     * @return callable
     */
    private function processRoutes(): callable
    {
        return function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
            $router = $this->dispatcher->dispatch(
                $request->getMethod(),
                $request->getUri()->getPath()
            );

            if ($router[0] === Dispatcher::NOT_FOUND) {
                $response = new JsonResponse([
                    'error' => 'Not found',
                    'code'  => 404,
                ], 404);
            }

            if ($router[0] === Dispatcher::METHOD_NOT_ALLOWED) {
                $response = new JsonResponse([
                    'error' => 'Not allowed',
                    'code'  => 405,
                ], 405);
            }

            $router[2]['request'] = $request;
            $router[2]['handler'] = $handler;
            // TODO: Check route middleware
            $response = $this->container->call($router[1], $router[2]);

            // TODO: Check the response
            return $response;
        };
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
        // (new \Whoops\Run())
        //     ->pushHandler((new \Whoops\Handler\JsonResponseHandler())->addTraceToOutput(true))
        //     ->pushHandler(new \Whoops\Handler\CallbackHandler(function ($exception) {
        //         $logger = container()->get(LoggerInterface::class);
        //         $logger->critical(
        //             $exception->getMessage(),
        //             ['exception' => $exception->__toString()]
        //         );
        //     }))
        //     ->register();
    }
}
