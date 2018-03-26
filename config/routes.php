<?php

return [
    [
        'methods'    => ['GET'],
        'path'       => '/recipes',
        'handlers'   => HelloFresh\Controllers\Recipes\GetRecipes::class,
        'middleware' => null
    ],
    [
        'methods'    => ['GET'],
        'path'       => '/info',
        'handlers'   => function () {
            phpinfo();
        },
        'middleware' => null
    ],
    [
        'methods'    => ['GET', 'POST'],
        'path'       => '/postinfo',
        'handlers'   => function () {
            phpinfo();
        },
        'middleware' => null
    ],
];
