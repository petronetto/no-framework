<?php

namespace Petronetto\Exceptions;

use Psr\Http\Message\ServerRequestInterface;

class NotAllowedHttpException extends BaseException
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct(ServerRequestInterface $request, Exception $previous = null)
    {
        $method  = $request->getMethod();
        $path    = $request->getUri()->getPath();
        $message = "Method {$method} is not allowed in path {$path}";

        parent::__construct($message, 405, $previous);
    }
}
