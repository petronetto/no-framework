<?php

declare(strict_types=1);

namespace Petronetto\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

abstract class AbstractController implements ControllerInterface
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
    }

    /**
     * Get the current in query string
     * or return a default value.
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
     * Send response as json.
     *
     * @param  array        $data
     * @param  int          $code
     * @return JsonResponse
     */
    protected function json(array $data, int $code = 200): JsonResponse
    {
        return new JsonResponse($data, $code);
    }
}
