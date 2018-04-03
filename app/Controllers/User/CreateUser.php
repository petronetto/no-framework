<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HelloFresh\Models\User;
use Respect\Validation\Validator as v;

/**
 * Create a new user
 *
 * @SWG\Post(
 *     path="/users",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"users"},
 *     security={
 *         {"Authorization": {}}
 *     },
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
 *         response=201,
 *         description="The created user",
 *         @SWG\Property(
 *             @SWG\Property(property="data", type="object", ref="#/definitions/UserApiResponse"),
 *         ),
 *         @SWG\Header(header="X-Powered-By", type="string", description=";)"),
 *         @SWG\Header(header="X-Response-Time", type="string", description="282.263ms"),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class CreateUser extends UsersBaseController
{
    /**
     * Create a new User
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $validation = v::key('username', v::alnum()->length(5, 30)->noWhitespace())
            ->key('email', v::email())
            ->key('first_name', v::alpha()->length(3, 30))
            ->key('last_name', v::alpha()->length(3, 30))
            ->key('password', v::alnum()->length(6, 36))
            ->keyValue('password_confirmation', 'equals', 'password');

        $validation->assert($data);

        $user = $this->service->create($data);

        return $this->json($user, 201);
    }
}
