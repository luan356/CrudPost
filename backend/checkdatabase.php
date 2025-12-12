<?php
$pdo = require __DIR__ . '/src/Config/Database.php';

$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_ASSOC);
print_r($tables);
