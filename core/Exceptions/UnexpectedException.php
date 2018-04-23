<?php

namespace Petronetto\Exceptions;

class UnexpectedException extends BaseException
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct(string $message = null, Exception $previous = null)
    {
        if (!$message) {
            $message = 'Whoops, something went wrong';
        }

        parent::__construct($message, 500, $previous);
    }
}
