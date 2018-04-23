<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Psr\Http\Message\ResponseInterface;

/**
 * Get one user by id
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
 *         @SWG\Header(header="X-Powered-By", type="string", description="A name of a dev guy ;)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 *     @SWG\Response(response=404, ref="#/definitions/Error"),
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
