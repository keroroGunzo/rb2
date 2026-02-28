<?php
require '../config/init.php';

header('Content-Type: application/json');

$response = [
    'success' => false
];

$id = isset($_GET['id']) && is_numeric($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if (!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak valid.'
    ]);
    exit;
}

try {

    // =========================
    // 1️⃣ Ambil Header
    // =========================
    $header = $db->single("
        SELECT 
            id,
            supplier_id,
            warehouse_id,
            total,
            created_at
        FROM purchases
        WHERE id = :id
        LIMIT 1
    ", [':id' => $id]);

    if (!$header) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
        exit;
    }

    // =========================
    // 2️⃣ Ambil Items
    // =========================
    $items = $db->query("
        SELECT 
            product_id,
            qty,
            cost_price
        FROM purchase_items
        WHERE purchase_id = :id
    ", [':id' => $id]);

    // =========================
    // 3️⃣ Gabungkan Response
    // =========================
    $response = $header;           // flatten header
    $response['success'] = true;
    $response['items'] = $items;
} catch (Exception $e) {

    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
