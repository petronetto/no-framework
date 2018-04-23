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
            'adapter' => $config[$config['default']]['driver'],
            'host'    => $config[$config['default']]['host'],
            'name'    => $config[$config['default']]['database'],
            'user'    => $config[$config['default']]['username'],
            'pass'    => $config[$config['default']]['password'],
            'port'    => $config[$config['default']]['port'],
        ],
        'testing' => [
            'adapter' => 'sqlite',
            'name'    => $config[$config['default']]['database'],
        ]
    ]
];
