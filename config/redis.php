<?php

declare(strict_types=1);

return [
    'scheme'   => getenv('REDIS_SCHEME'),
    'host'     => getenv('REDIS_HOST'),
    'port'     => getenv('REDIS_PORT'),
    'user'     => getenv('REDIS_USER'),
    'password' => getenv('REDIS_PASS'),
    'ttl'      => getenv('REDIS_TTL') ?: (60 * 60 * 24),
];
