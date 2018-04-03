<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

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
 *     ),
 *     @SWG\Response(response=404, description="User not found")
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
