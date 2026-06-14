<?php
$host = 'localhost';
$dbname = 'artcraft_store';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    session_start();
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
