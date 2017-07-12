<?php

usleep(300E3);

$key = 'key' . __DIR__;

$result = apcu_fetch($key);
if (!$result) {
    // let's assume we parse some XML/YAML file or make some action here
    $result = __DIR__;

    apcu_store($key, $result);
}

header('Content-Type: application/json');

echo json_encode([
    '__DIR__' => __DIR__,
    'cached __DIR__' => $result,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
