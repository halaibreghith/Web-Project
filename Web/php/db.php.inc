<?php
// Database connection details
$host = "localhost";
$dbname = "web1211002_project";
$username = "web1211002_dbuser";
$password = "root1211002";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>