<?php
require_once __DIR__ . '/../src/Connection.php';

// Database configuration
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'root',
    'db' => 'blog_db'
];

$db = new Connection($config['host'], $config['user'], $config['pass'], $config['db']);
$db->connect();

// Global $db variable to be used in other files
return $db;
