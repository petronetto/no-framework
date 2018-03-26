<?php

declare(strict_types=1);

if (!function_exists('config')) {
    /**
     * Get application configs.
     *
     * @return \Petronetto\Config
     */
    function config(): \Petronetto\Config
    {
        return \Petronetto\Config::getInstance();
    }
}

if (!function_exists('app')) {
    /**
     * Get application instance.
     *
     * @return \Petronetto\Application
     */
    function app(): \Petronetto\Application
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}

if (!function_exists('container')) {
    /**
     * Get the container instance.
     *
     * @return \Psr\Container\ContainerInterface
     */
    function container(): \Psr\Container\ContainerInterface
    {
        return app()->getContainer();
    }
}

if (!function_exists('request')) {
    /**
     * Get the request instance.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    function request(): \Psr\Http\Message\ServerRequestInterface
    {
        return container()->get('request');
    }
}

if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed $args
     * @return void
     */
    function dd(...$args): void
    {
        http_response_code(500);

        foreach ($args as $x) {
            dump($x);
        }

        die(1);
    }
}
