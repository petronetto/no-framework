<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Controllers\Controller;
use App\Services\UserService;

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
