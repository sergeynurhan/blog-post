<?php
require_once __DIR__ . '/../src/Connection.php';

// Change these to your database credentials
$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbName = 'blog_db';

$db = new Connection($host, $user, $pass);
$db->connect();

// Create Database
$db->query("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
$db->connection->select_db($dbName);

// Create Users Table
$db->createTable('users', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'username' => 'VARCHAR(255) NOT NULL UNIQUE',
    'email' => 'VARCHAR(255) NOT NULL UNIQUE',
    'password' => 'VARCHAR(255) NOT NULL',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
]);

// Create Topics Table
$db->createTable('topics', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(255) NOT NULL UNIQUE',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
]);

// Create News Table
$db->createTable('news', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'topic_id' => 'INT NOT NULL',
    'user_id' => 'INT NOT NULL',
    'title' => 'VARCHAR(255) NOT NULL',
    'content' => 'TEXT NOT NULL',
    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'FOREIGN KEY (topic_id)' => 'REFERENCES topics(id) ON DELETE CASCADE',
    'FOREIGN KEY (user_id)' => 'REFERENCES users(id) ON DELETE CASCADE'
]);

echo "Database and tables created successfully!\n";
