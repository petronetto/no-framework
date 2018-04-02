<?php

$router->group('/api/v1/', function ($router) {
    $router->get(
        'recipes',
        HelloFresh\Controllers\Recipes\GetRecipes::class,
        [HelloFresh\Middlewares\XPoweredBy::class]
    );
    $router->post('recipes', HelloFresh\Controllers\Recipes\CreateRecipe::class);
    $router->get('recipes/{id}', HelloFresh\Controllers\Recipes\GetRecipe::class);
    $router->put('recipes/{id}', HelloFresh\Controllers\Recipes\UpdateRecipe::class);
    $router->patch('recipes/{id}', HelloFresh\Controllers\Recipes\UpdateRecipe::class);
    $router->delete('recipes/{id}', HelloFresh\Controllers\Recipes\DeleteRecipe::class);
});
