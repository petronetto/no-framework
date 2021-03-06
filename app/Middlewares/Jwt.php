<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Services\AuthService;
use Lcobucci\JWT\Parser;
use Petronetto\Exceptions\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Jwt implements MiddlewareInterface
{
    /**
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UnauthorizedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->getHeader('Authorization')) {
            throw new UnauthorizedException('Token not present');
        }

        try {
            list($token) = $request->getHeader('Authorization');

            $token = (new Parser())->parse($token);
        } catch (\Throwable $t) {
            throw new UnauthorizedException('Invalid token');
        }

        (new AuthService)->validateToken($token);

        return $handler->handle($request);
    }
}
