<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $db = $client->streetlight_db;

    $collection = $db->complaints;

    $result = $collection->insertOne([
        'title' => 'Test streetlight complaint',
        'status' => 'pending',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    echo "MongoDB connection successful!<br>";
    echo "Inserted ID: " . $result->getInsertedId();

} catch (Exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}