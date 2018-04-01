<?php

namespace Petronetto\Middlewares;

use Petronetto\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Throwable;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

class ErrorResponseGenerator
{
    /**
     * @param Throwable $e
     */
    public static function handle(Throwable $e): ResponseInterface
    {
        switch ($e) {
            case $e instanceof NotFoundHttpException:
                $data = [
                    'type'      => get_class($e),
                    'message'   => $e->getMessage(),
                    'code'      => 404,
                ];

                break;
            case $e instanceof NestedValidationException:
                $data = [
                    'type'      => get_class($e),
                    'message'   => (function ($e) {
                        $msgs = explode('- ', $e->getFullMessage());
                        $msgs = array_filter($msgs);
                        $msgs = array_values(array_map('trim', $msgs));

                        return $msgs;
                    })($e),
                    'code'      => 422,
                ];

                break;
            case $e instanceof ValidationException:
                $data = [
                    'type'      => get_class($e),
                    'message'   => $e->getMessage(),
                    'code'      => 422,
                ];

                break;

            default:
                $data = [
                    'type'      => get_class($e),
                    'message'   => $e->getMessage(),
                    'code'      => 500,
                ];

                break;
        }

        if (isProd() && $data['code'] != 422) {
            $data['trace'] = $e->getTrace();
        }

        return self::json($data, $data['code']);
    }

    /**
     * @param  array                      $data
     * @param  integer                    $code
     * @return JsonResponse|EmptyResponse
     */
    private static function json(array $data = [], $code = 500)
    {
        return new JsonResponse($data, $code);
    }
}
