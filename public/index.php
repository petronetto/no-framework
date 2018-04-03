<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// error_reporting(E_ALL);

try {
    $app = new Petronetto\Application();
} catch (\Throwable $t) {
    // Catches any error that may occurs
    // before method run
    logError($t);
    bootstrapError($t);
}

$app->run();
