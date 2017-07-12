<?php

$pdo = new PDO('mysql:host=mysql;dbname=app;charset=utf8', 'root', 'pass', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('
    ALTER TABLE transfers DROP COLUMN money;

    ALTER TABLE transfers ALTER COLUMN amount DECIMAL NOT NULL;
    ALTER TABLE transfers ALTER COLUMN currency VARCHAR(255) NOT NULL;
');
