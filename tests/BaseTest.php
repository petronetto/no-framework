<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use HelloFresh\Services\AuthService;

abstract class BaseTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        $configFile = realpath(__DIR__ . '/../database/phinx.php');

        $phinxApp = new \Phinx\Console\PhinxApplication();
        $phinx = new \Phinx\Wrapper\TextWrapper($phinxApp);

        $phinx->setOption('configuration', $configFile);
        $phinx->setOption('parser', 'PHP');
        $phinx->setOption('environment', 'testing');

        $phinx->getMigrate();
        $phinx->getSeed();
    }

    public function getToken()
    {
        return (new AuthService)->getToken();
    }
}