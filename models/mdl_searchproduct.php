<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$term = trim($_GET['term'] ?? '');

$data = [];

if ($term) {

    $rows = $db->query("
        SELECT 
            id,
            name,
            sku,
            barcode,
            price_retail
        FROM products
        WHERE 
            name LIKE :term
            OR sku LIKE :term
            OR barcode LIKE :term
        LIMIT 20
    ",[
        ':term' => "%$term%"
    ]);

    foreach ($rows as $r) {

        $data[] = [
            "label" => $r['name']." | ".$r['sku']." | ".$r['barcode'],
            "value" => $r['name'],
            "product" => $r
        ];
    }
}

echo json_encode($data);