<?php

use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Middleware\JwtMiddleware;
use Slim\App;

return function (App $app) {

    // Auth
    $app->post('/auth/register', [AuthController::class, 'register']);
    $app->post('/auth/login', [AuthController::class, 'login']);

    // Posts (protegidas)
    $app->group('/posts', function ($group) {
        $group->get('', [PostController::class, 'index']);
        $group->get('/{id}', [PostController::class, 'show']);
        $group->post('', [PostController::class, 'store']);
        $group->put('/{id}', [PostController::class, 'update']);
        $group->delete('/{id}', [PostController::class, 'delete']);
    })->add(new JwtMiddleware());
};
