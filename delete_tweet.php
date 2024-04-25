<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

$sql = "DELETE FROM tweets WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$id]);

echo json_encode(['success' => $success]);
?>
