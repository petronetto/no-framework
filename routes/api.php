<?php

declare(strict_types=1);

return function (FastRoute\RouteCollector $router) {
    $router->get('/recipes', HelloFresh\Controllers\Recipes\GetRecipes::class);
    $router->get('/', function () {
        phpinfo();
    });
};
