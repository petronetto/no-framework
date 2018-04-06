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
        $config  = (Config::getInstance())->get('db');
        $db->addConnection($config[$config['default']]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }
}
