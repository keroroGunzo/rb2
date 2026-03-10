<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'data'    => null
];

$keyword = trim($_GET['barcode'] ?? '');

if (!$keyword) {
    $response['message'] = "Input kosong.";
    echo json_encode($response);
    exit;
}

try {

    $row = $db->single("
        SELECT 
            id,
            sku,
            barcode,
            name,
            price_retail,
            price_wholesale,
            min_wholesale_qty
        FROM products
        WHERE 
            barcode = :kw
            OR sku = :kw
            OR name LIKE :name
        LIMIT 1
    ", [
        ':kw'   => $keyword,
        ':name' => "%$keyword%"
    ]);

    if (!$row) {

        $response['message'] = "Produk tidak ditemukan.";
    } else {

        $response['success'] = true;
        $response['data'] = $row;
    }
} catch (Exception $e) {

    $response['message'] = $e->getMessage();
}

echo json_encode($response);
