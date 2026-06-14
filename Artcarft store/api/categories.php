<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
