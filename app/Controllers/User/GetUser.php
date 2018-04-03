<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

use Psr\Http\Message\ResponseInterface;

/**
 * Get one users by id
 *
 * @SWG\Get(
 *     path="/users/{id}",
 *     consumes={"application/json"},
 *     produces={"application/json"},
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
 *         response=200,
 *         description="The recieved user",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/UserApiResponse"),
 *         ),
 *     ),
 *     @SWG\Response(response=404, description="User not found")
 * )
 */
class GetUser extends UsersBaseController
{
    /**
     * Get One User by Id
     *
     * @param  int               $id
     * @return ResponseInterface
     */
    public function __invoke(int $id): ResponseInterface
    {
        $user = $this->service->getById($id);

        return $this->json($user, 200);
    }
}
