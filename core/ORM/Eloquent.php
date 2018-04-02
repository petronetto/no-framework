<?php

declare(strict_types=1);

namespace Petronetto\ORM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Petronetto\Config;

class Eloquent extends Model implements ORMInterface
{
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize the ORM
     *
     * @return void
     */
    public function initialize(): void
    {
        $db      = new Manager();
        $config  = Config::get('db');
        $db->addConnection([
            'driver'    => $config['driver'],
            'host'      => $config['host'],
            'database'  => $config['database'],
            'username'  => $config['username'],
            'password'  => $config['password'],
            'port'      => $config['port'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }
}
