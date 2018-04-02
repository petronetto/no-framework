<?php

declare(strict_types=1);

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = new Petronetto\Application();
} catch (\Throwable $t) {
    // Catches any error that may occurs
    // while the application is loading
    logError($t);
    bootstrapError($t);
}

return $app;
