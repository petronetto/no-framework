<?php

namespace Petronetto\Exceptions;

class NotFoundHttpException extends BaseException
{
    /**
     * Undocumented function
     *
     * @param string    $message
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
