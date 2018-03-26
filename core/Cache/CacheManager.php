<?php

declare(strict_types=1);

namespace Petronetto\Cache;

use Predis\Client as Redis;
use Psr\Container\ContainerInterface;

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
     * @param string $value
     * @return string|null
     */
    public function get(string $value): ?string
    {
        return $this->client->get($value);
    }

    /**
     * Set a value to cache
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set(string $key, string $value): void
    {
        $this->client->set($key, $value);
    }
}
