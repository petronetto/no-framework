<?php

$router->group('/api/v1/', function ($router) {
    //----------     Auth Middleware     ----------//
    $auth = App\Middlewares\Jwt::class;

    //----------     Auth Routes     ----------//
    $router->post('auth', App\Controllers\Auth\GetAuthToken::class);

    //----------     Recipe Routes     ----------//
    $router->get('recipes', App\Controllers\Recipe\GetRecipes::class);
    $router->post('recipes', App\Controllers\Recipe\CreateRecipe::class, [$auth]);
    $router->get('recipes/{id}', App\Controllers\Recipe\GetRecipe::class);
    $router->put('recipes/{id}', App\Controllers\Recipe\UpdateRecipe::class, [$auth]);
    $router->patch('recipes/{id}', App\Controllers\Recipe\UpdateRecipe::class, [$auth]);
    $router->delete('recipes/{id}', App\Controllers\Recipe\DeleteRecipe::class, [$auth]);
    $router->post('recipes/{id}/rating', App\Controllers\Recipe\Rating::class);

    //----------     User Routes     ----------//
    $router->get('users', App\Controllers\User\GetUsers::class, [$auth]);
    $router->post('users', App\Controllers\User\CreateUser::class, [$auth]);
    $router->get('users/{id}', App\Controllers\User\GetUser::class, [$auth]);
    $router->put('users/{id}', App\Controllers\User\UpdateUser::class, [$auth]);
    $router->patch('users/{id}', App\Controllers\User\UpdateUser::class, [$auth]);
    $router->delete('users/{id}', App\Controllers\User\DeleteUser::class, [$auth]);

    //----------     Delete cache     ----------//
    $router->delete('cache', App\Controllers\Cache\DeleteCache::class, [$auth]);
});
