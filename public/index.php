<?php

declare(strict_types=1);

error_reporting(E_ALL);

// header("Access-Control-Allow-Origin: *");

/*
|--------------------------------------------------------------------------
| Create the Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP requests from the environment.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->run();
