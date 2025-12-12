<?php

$pdo = require __DIR__ . '/src/Config/database.php';

$stmt = $pdo->query("
    SELECT name 
    FROM sqlite_master 
    WHERE type = 'table'
");

var_dump($stmt->fetchAll());
