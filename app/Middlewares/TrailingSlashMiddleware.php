<?php

namespace HelloFresh\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Remove a trailing slash from the URI.
 *
 * Class TrailingSlashMiddleware
 * @package Core\Middleware
 */
class TrailingSlashMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === '/') {
            return (new JsonResponse())
                ->withStatus(301)
                ->withHeader('Location', rtrim($uri, '/'));
        }

        return $handler->handle($request);
    }
}
