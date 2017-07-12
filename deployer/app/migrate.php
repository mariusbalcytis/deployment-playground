<?php

$pdo = new PDO('mysql:host=mysql;dbname=app;charset=utf8', 'root', 'pass', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('
    ALTER TABLE transfers ADD amount DECIMAL DEFAULT NULL;
    ALTER TABLE transfers ADD currency VARCHAR(255) DEFAULT NULL;
');

/*
    This one's for next deployment:

        UPDATE transfers
        SET amount = LEFT(money, LOCATE(" ", money) - 1),
            currency = SUBSTRING(money, LOCATE(" ", money) + 1);

    These are for one that's after it:

        ALTER TABLE transfers DROP COLUMN money;

        ALTER TABLE transfers ALTER COLUMN amount DECIMAL NOT NULL;
        ALTER TABLE transfers ALTER COLUMN currency VARCHAR(255) NOT NULL;
 */