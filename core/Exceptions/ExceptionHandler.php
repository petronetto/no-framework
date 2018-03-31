<?php

declare(strict_types=1);

namespace Petronetto\Exceptions;

use Exception;
use Respect\Validation\Exceptions\NestedValidationException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\EmptyResponse;

class ExceptionHandler
{
    /**
     * @param  Exception $e
     * @return Response
     */
    public static function handler(Exception $e)
    {
        if ($e instanceof UnauthorizedException) {
            return self::json(null, 401);
        }

        if ($e instanceof NestedValidationException) {
            return self::json([
                'type'    => get_class($e),
                'message' => $e->getFullMessage(),
            ], 422);
        }

        if ($e instanceof ValidationException) {
            return self::json([
                'type'    => get_class($e),
                'message' => $e->getMessage(),
            ], 422);
        }

        return new Response($e->getMessage(), 500);
    }

    private static function json($data = [], $code = 200)
    {
        if (empty($data)) {
            return new EmptyResponse();
        }

        return new JsonResponse($data, $code);
    }
}
