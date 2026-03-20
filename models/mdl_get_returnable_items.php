<?php
require '../config/init.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$purchase_id = isset($_GET['purchase_id']) ? (int)$_GET['purchase_id'] : 0;

if (!$purchase_id) {
    echo json_encode([
        "success" => false,
        "message" => "purchase_id kosong"
    ]);
    exit;
}

try {

    $rows = $db->query("
        SELECT
            pi.id AS purchase_item_id,
            pi.product_id,
            p.name,
            pi.qty AS purchased_qty,
            COALESCE(SUM(pri.qty),0) AS returned_qty,
            (pi.qty - COALESCE(SUM(pri.qty),0)) AS remaining_qty,
            pi.cost_price
        FROM purchase_items pi
        JOIN products p ON p.id = pi.product_id
        LEFT JOIN purchase_return_items pri
            ON pri.purchase_item_id = pi.id
        WHERE pi.purchase_id = :purchase_id
        GROUP BY pi.id, pi.product_id, p.name, pi.qty, pi.cost_price
        HAVING remaining_qty > 0
        ORDER BY p.name
    ", [
        ':purchase_id' => $purchase_id
    ]);

    echo json_encode([
        "success" => true,
        "data" => $rows
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}