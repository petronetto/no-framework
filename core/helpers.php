<?php

declare(strict_types=1);

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

if (!function_exists('config')) {
    /**
     * Get the container instance.
     *
     * @return \Petronetto\Config
     */
    function config(): \Petronetto\Config
    {
        return \Petronetto\Config::getInstance();
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

if (!function_exists('isProd')) {
    /**
     * Returns if application is running under prod env.
     *
     * @return bool
     */
    function isProd(): bool
    {
        return (bool) \Petronetto\Config::get('app.prod');
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

if (!function_exists('bootstrapError')) {
    /**
     * Returns a json in case bootstrap error.
     *
     * @param  \Throwable $t
     * @return void
     */
    function bootstrapError(\Throwable $t): void
    {
        $data = [
            'type'    => get_class($t),
            'message' => $t->getMessage(),
            'code'    => 500,
            'trace'   => $t->getTrace(),
        ];
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode($data);
        die(1);
    }
}

if (!function_exists('logError')) {
    /**
     * A simple logger to use in application bootstrap.
     *
     * @param  \Throwable $t
     * @return void
     */
    function logError(\Throwable $t): void
    {
        $message = sprintf(
            "[%s] Exception: %s\nTrace:\n%s",
            date('Y-m-d H:i:s'),
            $t->getMessage(),
            $t->getTraceAsString()
        );

        $file = __DIR__ . '/../log/app/error.log';

        $myfile = file_put_contents($file, $message.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
