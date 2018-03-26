<?php

declare(strict_types=1);

namespace Petronetto\Exceptions;

use Throwable;

class RouteException extends BaseException
{
    /** @var string */
    private $path;

    public function __construct($path, $message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
