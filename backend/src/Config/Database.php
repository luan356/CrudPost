<?php

$pdo = new \PDO('sqlite:' . __DIR__ . '/../../database.sqlite');
return $pdo;
