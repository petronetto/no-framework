<?php

declare(strict_types=1);

namespace App\Controllers\Cache;

use App\Controllers\Controller;
use App\Services\CacheService;

class CacheBaseController extends Controller
{
    /** @var CacheService */
    protected $cache;

    /**
     * Inject the cache in the base controller
     *
     * @param CacheService $cache
     */
    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }
}
