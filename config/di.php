<?php

declare(strict_types=1);

use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Petronetto\Config;
use Predis\Client as Redis;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

return [
    ContainerInterface::class => \DI\factory(function (ContainerInterface $container) {
        return $container;
    }),
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
    ResponseInterface::class       => DI\get('response'),
    RequestInterface::class        => DI\get('request'),
    ServerRequestInterface::class  => DI\get('request'),
    LoggerInterface::class         => function () {
        $appName   = config()->get('application.name');
        $logDir    = config()->get('application.logdir');
        $logger    = new Logger($appName);
        $handler   = new RotatingFileHandler(
            "{$logDir}/{$appName}.log",
            10,
            Logger::DEBUG
        );
        $format    = "[%datetime%]\n%channel%.%level_name%: %message% %context%\n";
        $formatter = new LineFormatter($format, null, true, true);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        ErrorHandler::register($logger);
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
    Redis::class     => DI\get('cache'),
];
