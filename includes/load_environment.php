<?php

$file_path = __DIR__ . "/../.env";

if (!file_exists($file_path)) {
    throw new Exception("$file_path n'existe pas.");
}

$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (strpos(trim($line), "#") === 0) {
        continue;
    }
    
    list($key, $value) = explode("=", $line, 2);
    $key = trim($key);
    $value = trim($value);
    $_ENV[$key] = $value;
}
