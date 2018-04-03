<?php

declare(strict_types=1);

namespace HelloFresh\Controllers\Cache;

use HelloFresh\Controllers\Controller;
use HelloFresh\Services\CacheService;

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
