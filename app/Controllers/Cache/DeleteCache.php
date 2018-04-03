<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Cache;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Delete the application cache
 *
 * @SWG\Delete(
 *     path="/cache",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"cache"},
 *     security={
 *         {"Authorization": {}}
 *     },
 *     @SWG\Response(
 *         response=204,
 *         description="An empty response",
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class DeleteCache extends CacheBaseController
{
    /**
     * @return ResponseInterface
     */
    public function __invoke(): ResponseInterface
    {
        $this->cache->flushAll();

        return new EmptyResponse();
    }
}
