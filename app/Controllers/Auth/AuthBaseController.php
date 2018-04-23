<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Services\AuthService;

class AuthBaseController extends Controller
{
    /** @var AuthService */
    protected $auth;

    /**
     * Inject the service in the base controller
     *
     * @param UserService $service
     */
    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }
}
