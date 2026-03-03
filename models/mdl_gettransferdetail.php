<?php
require '../config/init.php';

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    echo json_encode(['success'=>false,'message'=>'ID tidak valid']);
    exit;
}

$rows = $db->query("
    SELECT 
        p.sku,
        p.name,
        sm.qty
    FROM stock_movements sm
    JOIN products p ON p.id = sm.product_id
    WHERE sm.reference_id = :id
    AND sm.movement_type = 'transfer'
", [':id'=>$id]);

echo json_encode([
    'success'=>true,
    'data'=>$rows
]);