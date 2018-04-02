<?php

declare(strict_types=1);

namespace HelloFresh\Services;

use Petronetto\Config;
use Predis\Client as Redis;

class CacheService
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
            $ttl = (int) Config::get('redis.ttl');
        }
        $this->client->expire($key, $ttl);
    }

    /**
     * Get keys by pattern
     *
     * @param  array $pattern
     * @return array
     */
    public function keys($pattern): array
    {
        return $this->client->keys($pattern);
    }

    /**
     * Delete by keys
     *
     * @param  array $keys
     * @return void
     */
    public function del(array ...$keys): void
    {
        $this->client->del(...$keys);
    }

    /**
     * Delete all keys that match with pattern
     *
     * @param  array $pattern
     * @return array
     */
    public function delKeys(string $pattern): void
    {
        $keys = $this->keys($pattern);
        if ($keys) {
            $this->del($keys);
        }
    }
}
