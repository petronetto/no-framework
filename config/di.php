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
        $appName   = Config::get('application.name');
        $logDir    = Config::get('application.logdir');
        $logger    = new Logger($appName);
        $handler   = new RotatingFileHandler("{$logDir}/{$appName}.log");
        $logger->pushHandler($handler);

        return $logger;
    },
    'cache' => function () {
        $redisConf = Config::get('redis');

        return new Redis([
            'scheme' => $redisConf['scheme'],
            'host'   => $redisConf['host'],
            'port'   => $redisConf['port'],
        ]);
    },
    Redis::class                    => DI\get('cache'),
    ServerRequestInterface::class   => DI\get('request'),
];
