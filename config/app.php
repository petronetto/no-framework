<?php

return [
    'env'      => getenv('APP_ENV') ?: 'prod',
    'key'      => getenv('APP_KEY') ?: 'somethingreallylong',
    'url'      => getenv('APP_URL') ?: 'http://localhost',
    'port'     => getenv('APP_PORT') ?: 80,
    'name'     => getenv('APP_NAME') ?: 'nofw',
    'cachedir' => getenv('APP_CACHE_DIR') ?: __DIR__ . '/../tmp',
    'logdir'   => getenv('APP_LOG_DIR') ?: __DIR__ . '/../log/app',
    'pagesize' => 15,
    'prod'     => (function () {
        $prodNames = ['prd', 'prod', 'production'];
        $appEnv    = getenv('APP_ENV') ?: 'prod';

        if (in_array($appEnv, $prodNames)) {
            return true;
        }

        return false;
    })(),
];
