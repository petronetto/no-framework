<?php

declare(strict_types=1);

// error_reporting(E_ALL);

try {
    require_once __DIR__ . '/../vendor/autoload.php';

    $app = new Petronetto\Application();

    $app->run();
} catch (\Throwable $t) {
    // Catches any error that may occurs
    // before the run method
    logError($t);
    bootstrapError($t);
}
