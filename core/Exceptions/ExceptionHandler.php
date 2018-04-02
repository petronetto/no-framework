<?php

namespace Petronetto\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Exceptions\ValidationException;
use Throwable;
use Zend\Diactoros\Response\JsonResponse;

class ExceptionHandler
{
    /**
     * @param Throwable $t
     */
    public static function handle(Throwable $t, bool $isProd): ResponseInterface
    {
        switch ($t) {
            case $t instanceof NotFoundHttpException:
                $data = [
                    'type'      => get_class($t),
                    'message'   => $t->getMessage(),
                    'code'      => 404,
                ];

                break;
            case $t instanceof ValidationException:
                $data = [
                    'type'      => get_class($t),
                    'message'   => (function ($t) {
                        $msgs = explode('- ', $t->getFullMessage());
                        $msgs = array_filter($msgs);
                        $msgs = array_values(array_map('trim', $msgs));

                        return $msgs;
                    })($t),
                    'code'      => 422,
                ];

                break;

            default:
                $data = [
                    'type'      => get_class($t),
                    'message'   => $t->getMessage(),
                    'code'      => 500,
                ];

                break;
        }

        if (!$isProd && $data['code'] != 422) {
            $data['trace'] = $t->getTrace();
        }

        return self::sendResponse($data, $data['code']);
    }

    /**
     * @param  array             $data
     * @param  integer           $code
     * @return ResponseInterface
     */
    private static function sendResponse(array $data = [], $code = 500): ResponseInterface
    {
        return new JsonResponse($data, $code);
    }
}
