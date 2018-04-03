<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\User;

use HelloFresh\Controllers\Controller;
use HelloFresh\Services\UserService;

class UsersBaseController extends Controller
{
    /** @var UserService */
    protected $service;

    /**
     * Inject the service in the base controller
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
}
