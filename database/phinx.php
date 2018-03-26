<?php declare(strict_types=1);

$config = require __DIR__ . '/../config/db.php';

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations',
        'seeds'      => __DIR__ . '/seeds',
    ],

    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => $config['driver'],
            'host'    => $config['host'],
            'name'    => $config['database'],
            'user'    => $config['username'],
            'pass'    => $config['password'],
            'port'    => $config['port'],
        ]
    ]
];
