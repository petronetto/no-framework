<?php

declare(strict_types=1);

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
