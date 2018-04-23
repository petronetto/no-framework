<?php

namespace Petronetto\Exceptions;

class ForbiddenException extends BaseException
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct(string $message = null, Exception $previous = null)
    {
        if (!$message) {
            $message = 'Forbidden';
        }

        parent::__construct($message, 403, $previous);
    }
}
