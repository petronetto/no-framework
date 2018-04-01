<?php

namespace HelloFresh\Middlewares;

use Petronetto\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class Cors implements MiddlewareInterface
{
    /**
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response  = $handler->handle($request);

        $headers = Config::getInstance()->get('cors');

        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse([
                'method' => 'OPTIONS'
            ], 200, $headers);
        }

        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        return $response;
    }
}
