<?php

return [
    [
        'methods'     => ['GET'],
        'path'        => '/recipes',
        'handler'     => HelloFresh\Controllers\Recipes\GetRecipes::class,
        'middlewares' => HelloFresh\Middlewares\ResponseTime::class,
    ],
    [
        'methods'     => ['GET'],
        'path'        => '/recipes/{id}',
        'handler'     => HelloFresh\Controllers\Recipes\GetRecipe::class
    ],
    [
        'methods'     => ['POST'],
        'path'        => '/recipes',
        'handler'     => HelloFresh\Controllers\Recipes\CreateRecipe::class
    ],
    [
        'methods'     => ['GET'],
        'path'        => '/postinfo',
        'handler'     => function () {
            phpinfo();
        },
        'middlewares' => null
    ],
];
