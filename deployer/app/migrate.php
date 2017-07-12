<?php

$pdo = new PDO('mysql:host=mysql;dbname=app;charset=utf8', 'root', 'pass', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('
    ALTER TABLE transfers MODIFY money VARCHAR(255);
    
    UPDATE transfers
    SET amount = LEFT(money, LOCATE(" ", money) - 1),
        currency = SUBSTRING(money, LOCATE(" ", money) + 1);
');

/*
    This one's for next deployment:

        ALTER TABLE transfers DROP COLUMN money;

        ALTER TABLE transfers ALTER COLUMN amount DECIMAL NOT NULL;
        ALTER TABLE transfers ALTER COLUMN currency VARCHAR(255) NOT NULL;
 */