<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'ramarama';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    // Using utf8mb4 charset for better support of special characters and emojis
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
} catch (PDOException $e) {
    // Log the error (rather than showing it to the user directly)
    error_log('Connection failed: ' . $e->getMessage(), 3, 'errors.log');
    echo 'There was a problem connecting to the database. Please try again later.';
    exit;
}
?>
