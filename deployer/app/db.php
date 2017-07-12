<?php

$pdo = new PDO('mysql:host=mysql;dbname=app;charset=utf8', 'root', 'pass', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('INSERT INTO transfers (amount, currency) VALUES (1, "EUR")');
$id = $pdo->lastInsertId();

$statement = $pdo->prepare('SELECT * FROM transfers WHERE id < :id ORDER BY id DESC LIMIT 10');
$statement->execute([':id' => $id]);
$transfers = $statement->fetchAll(PDO::FETCH_ASSOC);

$sum = 0;
foreach ($transfers as $transfer) {
    $sum += $transfer['amount'];
}

header('Content-Type: application/json');

echo json_encode([
    'expected' => count($transfers),
    'sum' => $sum,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
