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

$sql = "SELECT sku, barcode, name AS nama_produk, price_retail AS harga_retail, price_wholesale AS harga_grosir, min_wholesale_qty AS jumlah_minimal_grosir, cost_price AS harga_beli
        FROM products 
        WHERE id = :id";

$result = $db->single($sql, [':id' => $id]);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['error' => true, 'message' => 'Produk tidak ditemukan']);
}
