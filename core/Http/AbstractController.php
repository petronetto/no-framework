<?php

declare(strict_types=1);

namespace Petronetto\Http;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

abstract class AbstractController implements ControllerInterface
{
    /**
     * Parse the data to array
     *
     * @param  ServerRequestInterface $request
     * @return array
     */
    public function parseRequestDataToArray(ServerRequestInterface $request): ?array
    {
        return json_decode($request->getBody()->getContents(), true);
    }

    /**
     * Get the current in query string
     * or return a default value
     *
     * @param  ServerRequestInterface $request
     * @return int
     */
    public function getCurrentPage(ServerRequestInterface $request): int
    {
        $queryParams = $request->getQueryParams();

        return isset($queryParams['page'])
            ? (int) $queryParams['page']
            : 1;
    }

    /**
     * Get the page size in query string
     * or return a default value.
     *
     * @param  ServerRequestInterface $request
     * @return int
     */
    public function getPageSize(ServerRequestInterface $request): int
    {
        $queryParams = $request->getQueryParams();

        return isset($queryParams['per_page'])
            ? (int) $queryParams['per_page']
            : (int) config()->get('application.pagesize');
    }

    /**
     * Send response as json
     *
     * @param  array        $content
     * @param  int          $code
     * @param  array        $headers
     * @return JsonResponse
     */
    protected function json(array $content, int $code = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($content, $code, $headers);
    }
}
