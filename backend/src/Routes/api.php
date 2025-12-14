<?php

use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Middleware\JwtMiddleware;
use Slim\App;

return function (App $app) {

    $app->post('/auth/register', [AuthController::class, 'register']);
    $app->post('/auth/login', [AuthController::class, 'login']);

    

   $app->group('/posts', function ($group) {
    $group->get('', [PostController::class, 'index']);
    $group->get('/{id}', [PostController::class, 'show']);
    $group->post('', [PostController::class, 'create']);
    $group->put('/{id}', [PostController::class, 'update']);
    $group->delete('/{id}', [PostController::class, 'delete']);
})->add(new JwtMiddleware());
};
