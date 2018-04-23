<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Delete one user
 *
 * @SWG\Delete(
 *     path="/users/{id}",
 *     consumes={"application/json"},
 *     tags={"users"},
 *     security={
 *         {"Authorization": {}}
 *     },
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="User id",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Response(
 *         response=204,
 *         description="An empty response",
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 *     @SWG\Response(response=404, ref="#/definitions/Error"),
 * )
 */
class DeleteUser extends UsersBaseController
{
    /**
     * @param  int               $id
     * @return ResponseInterface
     */
    public function __invoke(int $id): ResponseInterface
    {
        $this->service->delete($id);

        return new EmptyResponse();
    }
}
