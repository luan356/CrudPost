<?php

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// PDO via middleware
$app->add(function ($request, $handler) {
    $pdo = require __DIR__ . '/../src/Config/database.php';
    return $handler->handle(
        $request->withAttribute('db', $pdo)
    );
});

// Routes
(require __DIR__ . '/../src/Routes/api.php')($app);

$app->run();
