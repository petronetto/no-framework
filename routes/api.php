<?php

$router->group('/api/v1/', function ($router) {
    //----------     Auth Middleware     ----------//
    $auth = HelloFresh\Middlewares\Jwt::class;

    //----------     Auth Routes     ----------//
    $router->post('auth', HelloFresh\Controllers\Auth\GetAuthToken::class);

    //----------     Recipe Routes     ----------//
    $router->get('recipes', HelloFresh\Controllers\Recipe\GetRecipes::class);
    $router->post('recipes', HelloFresh\Controllers\Recipe\CreateRecipe::class, [$auth]);
    $router->get('recipes/{id}', HelloFresh\Controllers\Recipe\GetRecipe::class);
    $router->put('recipes/{id}', HelloFresh\Controllers\Recipe\UpdateRecipe::class, [$auth]);
    $router->patch('recipes/{id}', HelloFresh\Controllers\Recipe\UpdateRecipe::class, [$auth]);
    $router->delete('recipes/{id}', HelloFresh\Controllers\Recipe\DeleteRecipe::class, [$auth]);

    //----------     User Routes     ----------//
    $router->get('users', HelloFresh\Controllers\User\GetUsers::class, [$auth]);
    $router->post('users', HelloFresh\Controllers\User\CreateUser::class, [$auth]);
    $router->get('users/{id}', HelloFresh\Controllers\User\GetUser::class, [$auth]);
    $router->put('users/{id}', HelloFresh\Controllers\User\UpdateUser::class, [$auth]);
    $router->patch('users/{id}', HelloFresh\Controllers\User\UpdateUser::class, [$auth]);
    $router->delete('users/{id}', HelloFresh\Controllers\User\DeleteUser::class, [$auth]);

    //----------     Delete cache     ----------//
    $router->delete('cache', HelloFresh\Controllers\Cache\DeleteCache::class, [$auth]);
});
