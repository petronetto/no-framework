<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Auth;

use Petronetto\Exceptions\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;

/**
 * Get a JWT Token
 *
 * @SWG\Post(
 *     path="/auth",
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     tags={"auth"},
 *     @SWG\Parameter(
 *         name="payload",
 *         in="body",
 *         description="Auth payload",
 *         required=true,
 *         @SWG\Schema(
 *             @SWG\Property(property="username", type="string", example="johndoe"),
 *             @SWG\Property(property="password", type="string", example="secret"),
 *         )
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="JWT Token",
 *         @SWG\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIn0..."),
 *     ),
 *     @SWG\Response(response=401, ref="#/definitions/Error"),
 * )
 */
class GetAuthToken extends AuthBaseController
{
    /**
     * @param  ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->parseRequestDataToArray($request);

        $validation = v::key('username')->key('password');

        $validation->assert($data);

        $userId = $this->auth->authorize($data['username'], $data['password']);

        $token = $this->auth->getToken($userId);

        return $this->json(['token' => $token]);
    }
}
