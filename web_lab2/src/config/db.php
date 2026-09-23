<?php
$host = 'db';        // service name from docker-compose.yml, NOT localhost
$dbname = 'myapp';
$user = 'appuser';
$pass = 'apppass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}