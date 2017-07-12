<?php

usleep(30E3);

header('Content-Type: application/json');

$included = require './include.php';

echo json_encode([
    '__DIR__' => __DIR__,
    'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'],
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
    'included __DIR__' => $included,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
