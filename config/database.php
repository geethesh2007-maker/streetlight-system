<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $db = $client->streetlight_db;
} catch (Exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

?>