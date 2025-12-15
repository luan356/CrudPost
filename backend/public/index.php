<?php

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use Slim\Psr7\Response;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Load ENV
 */
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Create App
 */
$app = AppFactory::create();

/**
 * Body parser (JSON, form-data, etc)
 */
$app->addBodyParsingMiddleware();

/**
 * 🔥 CORS + OPTIONS (ESSENCIAL PARA O REACT)
 */
$app->add(function ($request, $handler) {

    // Preflight request
    if ($request->getMethod() === 'OPTIONS') {
        $response = new Response();
    } else {
        $response = $handler->handle($request);
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

/**
 * PDO via middleware (injeção no request)
 */
$app->add(function ($request, $handler) {
    $pdo = require __DIR__ . '/../src/Config/database.php';

    return $handler->handle(
        $request->withAttribute('db', $pdo)
    );
});

/**
 * Routes (NÃO MEXEMOS NELAS)
 */
(require __DIR__ . '/../src/Routes/api.php')($app);

/**
 * Run app
 */
$app->run();
