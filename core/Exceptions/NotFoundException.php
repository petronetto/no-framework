<?php

namespace Petronetto\Exceptions;

use Exception;

class NotFoundException extends BaseException
{
    /**
     * @param string    $request
     * @param Exception $previous
     */
    public function __construct(string $message = null, Exception $previous = null)
    {
        if (!$message) {
            $message = 'Resource not found';
        }

        parent::__construct($message, 404, $previous);
    }
}
