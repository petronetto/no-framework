<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Petronetto\Config;
use Predis\Client as Redis;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

return [
    'emitter'  => DI\create(SapiEmitter::class),
    'response' => DI\create(Response::class),
    'request'  => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    },
    'logger' => function () {
        $appName   = config()->get('app.name');
        $logDir    = config()->get('app.logdir');
        $logger    = new Logger($appName);
        $handler   = new RotatingFileHandler("{$logDir}/{$appName}.log");
        $logger->pushHandler($handler);

        return $logger;
    },
    'cache' => function () {
        $redisConf = config()->get('redis');

        return new Redis([
            'scheme' => $redisConf['scheme'],
            'host'   => $redisConf['host'],
            'port'   => $redisConf['port'],
        ]);
    },
    Redis::class                    => DI\get('cache'),
    ServerRequestInterface::class   => DI\get('request'),
];
