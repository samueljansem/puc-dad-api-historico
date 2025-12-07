<?php

$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: '';
$port = getenv('DB_PORT') ?: 3306;
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASSWORD') ?: '';

try {
  $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed']);
  error_log('Database connection error: ' . $e->getMessage());
  exit;
}

?>