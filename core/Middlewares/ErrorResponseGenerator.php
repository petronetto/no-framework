<?php

namespace Petronetto\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\Response\JsonResponse;

class ErrorResponseGenerator
{
    /** @var boolean */
    private $isDevelopmentMode;

    /**
     * @param boolean $isDevelopmentMode
     */
    public function __construct($isDevelopmentMode = false)
    {
        $this->isDevelopmentMode = $isDevelopmentMode;
    }

    /**
     * @param  Throwable              $e
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(
        Throwable $e,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return new JsonResponse([
            'exception' => $e->getMessage(),
            'trace'     => $e->getTrace(),
        ], 500);
    }
}
