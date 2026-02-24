<?php
require '../config/init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['error' => true, 'message' => 'ID tidak valid']);
    exit;
}

$sql = "SELECT id,name,address,created_at 
        FROM warehouses 
        WHERE id = :id";

$result = $db->single($sql, [':id' => $id]);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['error' => true, 'message' => 'Customer tidak ditemukan']);
}
