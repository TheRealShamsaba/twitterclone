<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$content = $_POST['content'] ?? '';

$sql = "UPDATE tweets SET content = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([$content, $id]);

echo json_encode(['success' => $success]);
?>
