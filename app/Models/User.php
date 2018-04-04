<?php

declare(strict_types=1);

namespace HelloFresh\Models;

/**
 * Class User
 *
 * @SWG\Definition(
 *     definition="User",
 *     type="object",
 *     @SWG\Property(property="id", type="integer", example=10),
 *     @SWG\Property(property="username", type="string", example="jhondoe"),
 *     @SWG\Property(property="email", type="string", example="john@doe.com"),
 *     @SWG\Property(property="first_name", type="string", example="Jhon"),
 *     @SWG\Property(property="last_name", type="string", example="Doe"),
 *     @SWG\Property(property="password", type="string", example="secret"),
 *     @SWG\Property(property="created_at", type="string", example="2018-04-01 12:00:00"),
 *     @SWG\Property(property="updated_at", type="string", example="2018-04-01 12:00:00"),
 * )
 */
class User extends Model
{
    /** {@inheritdoc} */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Hash the password.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }
}
