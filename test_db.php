<?php
require_once __DIR__ . '/vendor/autoload.php'; // Adjusted path if vendor is in the same folder

// 1. Load the Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host   = $_ENV['DB_HOST'];
$db     = $_ENV['DB_NAME'];
$user   = $_ENV['DB_USER'];
$pass   = $_ENV['DB_PASS'];
$port   = $_ENV['DB_PORT'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     echo "try pdo";
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "✅ Success! PHP is talking to the Database.";
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
     // echo "❌ Connection failed: " . $e->getMessage();
}