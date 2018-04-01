<?php

declare(strict_types=1);

namespace Petronetto;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Petronetto\Middlewares\ErrorResponseGenerator;
use Petronetto\Middlewares\NotFoundMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Stratigility\MiddlewarePipe;
use Petronetto\Routing\Router;

use function Zend\Stratigility\middleware;

class Application
{
    /** @var ContainerInterface */
    private $container;

    /** @var Config */
    private $config;

    /** @var Dispatcher */
    public $dispatcher;

    /** @var RouteCollector */
    public $router;

    /**
     * Application constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
        $this->bootErrorHandler();
        $this->config    = Config::getInstance();
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
     * Emitt the response
     *
     * @return void
     */
    public function run()
    {
        $pipeline = new MiddlewarePipe();

        $request = $this->container->get('request');

        // Putting middlewares in pipeline
        $middlewares = $this->config->get('middlewares');
        foreach ($middlewares as $middleware) {
            $pipeline->pipe(new $middleware());
        }

        // setup error handling
        $pipeline->pipe(
            new ErrorHandler(
                function (): ResponseInterface {
                    return $this->container->get('response');
                },
                new ErrorResponseGenerator(true)
            )
        );

        // Getting requested route details
        $routes = $this->router->getRoutes($request);

        // Putting middlewares for the found route in pipeline
        foreach ($routes['middlewares'] as $middleware) {
            $pipeline->pipe(new $middleware());
        }

        // Processing route
        $pipeline->pipe(
            middleware(function (
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ) use ($routes) {
                $routes['params']['request'] = $request;
                $routes['params']['handler'] = $handler;

                return $this->container->call($routes['handler'], $routes['params']);
            })
        );

        // Ok... For now...
        $pipeline->pipe(new NotFoundMiddleware());

        $response = $pipeline->handle($request);

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
     * Check if application is running in prod
     *
     * @return boolean
     */
    public function isProd(): bool
    {
        return (bool) $this->config->get('application.prod');
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
