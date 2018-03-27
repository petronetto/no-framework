<?php

declare(strict_types=1);

namespace Petronetto\Cache;

use Predis\Client as Redis;

class CacheManager
{
    /** @var \Predis\Client  */
    protected $client;

    public function __construct(Redis $client)
    {
        $this->client = $client;
    }

    /**
     * Get key value in cache
     *
     * @param  string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if ($cahed = $this->client->get($key)) {
            return unserialize($cahed);
        }

        return null;
    }

    /**
     * Set a value to cache
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  int    $ttl
     * @return void
     */
    public function set(string $key, $value, int $ttl = null): void
    {
        $this->client->set($key, serialize($value));

        if (!$ttl) {
            $ttl = (int) config()->get('redis.ttl');
        }
        $this->client->expire($key, $ttl);
    }
}
