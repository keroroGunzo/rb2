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

$sql = "SELECT id,DATE(expense_date) as tanggal,description as keterangan,created_at,category_id as kategori_id,amount as jumlah 
        FROM expenses 
        WHERE id = :id";

$result = $db->single($sql, [':id' => $id]);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['error' => true, 'message' => 'Expense tidak ditemukan']);
}
