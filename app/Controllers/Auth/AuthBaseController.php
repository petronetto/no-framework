<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Auth;

use HelloFresh\Controllers\Controller;
use HelloFresh\Services\AuthService;

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
