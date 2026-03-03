<?php
require '../config/init.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$output = ['data'=>[]];

if (!$type || !$id) {
    echo json_encode($output);
    exit;
}

$rows = $db->query("
    SELECT 
        s.product_id,
        p.sku,
        p.name,
        s.qty
    FROM stocks s
    JOIN products p ON p.id = s.product_id
    WHERE s.location_type = :type
    AND s.location_id = :id
    AND s.qty > 0
    AND s.stock_status = 'sellable'
    ORDER BY p.name ASC
", [
    ':type'=>$type,
    ':id'=>$id
]);

foreach ($rows as $row) {
    $output['data'][] = $row;
}

echo json_encode($output);