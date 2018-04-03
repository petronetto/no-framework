<?php

declare(strict_types=1);

namespace HelloFresh\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * API Response for Users
 *
 * @SWG\Definition(
 *     definition="UserApiResponse",
 *     type="object",
 *     @SWG\Property(property="id", type="integer", example=10),
 *     @SWG\Property(property="username", type="string", example="jhondoe"),
 *     @SWG\Property(property="email", type="string", example="john@doe.com"),
 *     @SWG\Property(property="first_name", type="string", example="Jhon"),
 *     @SWG\Property(property="last_name", type="string", example="Doe"),
 * )
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Transform any default or included data
     * into a basic array.
     *
     * @param  array $user
     * @return array
     */
    public function transform(array $user)
    {
        return [
            'id'         => (int) $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
        ];
    }
}
