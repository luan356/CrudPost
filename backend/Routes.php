<?php

use App\Controllers\AuthController;
use App\Middleware\JwtMiddleware;
use App\Controllers\PostController;


return function ($app) {

    $app->post('/auth/register', [AuthController::class, 'register']);
    $app->post('/auth/login', [AuthController::class, 'login']);


    $app->get('/posts', [PostController::class, 'index']);
    $app->get('/posts/{id}', [PostController::class, 'show']);

    $app->group('/posts', function ($group) {
        $group->post('', 'App\Controllers\PostController:create');
        $group->put('/{id}', 'App\Controllers\PostController:update');
        $group->delete('/{id}', 'App\Controllers\PostController:delete');
    })->add(new JwtMiddleware());

};
