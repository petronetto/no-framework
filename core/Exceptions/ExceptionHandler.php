<?php

declare(strict_types=1);

namespace Petronetto\Exceptions;

use Exception;
use PDOException;
use Respect\Validation\Exceptions\NestedValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler
{
    /**
     * @param  Exception $e
     * @return Response
     */
    public static function handler(Exception $e)
    {
        if ($e instanceof PDOException) {
            return self::json([
                'message' => sprintf('Database encountered some problems and returned %s', $e->getCode()),
            ], 500);
        }

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
            return new Response(null, $code);
        }

        return new JsonResponse($data, $code);
    }
}
