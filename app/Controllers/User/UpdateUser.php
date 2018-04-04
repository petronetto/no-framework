<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

/**
 * Update one user
 *
 * @SWG\Put(
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
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="User payload",
 *         required=true,
 *         @SWG\Schema(
 *             @SWG\Property(property="id", type="integer", example=10),
 *             @SWG\Property(property="username", type="string", example="jhondoe"),
 *             @SWG\Property(property="email", type="string", example="john@doe.com"),
 *             @SWG\Property(property="first_name", type="string", example="Jhon"),
 *             @SWG\Property(property="last_name", type="string", example="Doe"),
 *             @SWG\Property(property="password", type="string", example="secret"),
 *             @SWG\Property(property="password_confirmation", type="string", example="secret"),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="The updated user",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/UserApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 * )
 *
 * @SWG\Patch(
 *     path="/users/{id}",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"users"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="User id",
 *         required=true,
 *         type="string"
 *     ),
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="User payload",
 *         required=true,
 *         @SWG\Schema(
 *             @SWG\Property(property="id", type="integer", example=10),
 *             @SWG\Property(property="username", type="string", example="jhondoe"),
 *             @SWG\Property(property="email", type="string", example="john@doe.com"),
 *             @SWG\Property(property="first_name", type="string", example="Jhon"),
 *             @SWG\Property(property="last_name", type="string", example="Doe"),
 *             @SWG\Property(property="password", type="string", example="secret"),
 *             @SWG\Property(property="password_confirmation", type="string", example="secret"),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="The updated user",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/UserApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 *     @SWG\Response(response=404, ref="#/definitions/Error"),
 * )
 */
class UpdateUser extends UsersBaseController
{
    /**
     * @param  ServerRequestInterface $request
     * @param  int                    $id
     * @return ResponseInterface
     * @throws ValidationException
     */
    public function __invoke(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $request->getMethod() === 'PATCH'
            ? $required = false
            : $required = true;

        $validation = v::key('username', v::alnum()->length(5, 30)->noWhitespace(), $required)
            ->key('email', v::email(), $required)
            ->key('first_name', v::alpha()->length(3, 30), $required)
            ->key('last_name', v::alpha()->length(3, 30), $required);

        $validation->assert($data);

        $user = $this->service->update($data, $id);

        return $this->json($user, 200);
    }
}
