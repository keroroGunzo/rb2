<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$output = ['data' => []];

try {

    $rows = $db->query("
        SELECT 
            sm.id,
            sm.product_id,
            sm.from_type,
            sm.from_id,
            sm.to_type,
            sm.to_id,
            sm.qty,
            sm.movement_type,
            sm.created_at,
            p.name AS product_name,
            p.sku,
            u.name AS user_name
        FROM stock_movements sm
        JOIN products p ON p.id = sm.product_id
        LEFT JOIN users u ON u.id = sm.user_id
        ORDER BY sm.created_at DESC
    ");

    $no = 1;

    foreach ($rows as $row) {

        $fromName = getLocationName($db, $row['from_type'], $row['from_id']);
        $toName   = getLocationName($db, $row['to_type'], $row['to_id']);

        $output['data'][] = [
            'no'      => $no++,
            'date'    => date('d M Y H:i:s', strtotime($row['created_at'])),
            'product' => $row['sku'] . ' - ' . $row['product_name'],
            'from'    => $fromName,
            'to'      => $toName,
            'qty'     => $row['qty'],
            'type'    => ucfirst($row['movement_type']),
            'user'    => $row['user_name'] ?? '-'
        ];
    }
} catch (Exception $e) {

    $output = [
        'error' => true,
        'message' => $e->getMessage()
    ];
}

echo json_encode($output);


function getLocationName($db, $type, $id)
{
    if (!$type || !$id) return '-';

    if ($type === 'warehouse') {
        $row = $db->single(
            "SELECT name FROM warehouses WHERE id = :id",
            [':id' => $id]
        );
    } else {
        $row = $db->single(
            "SELECT name FROM stores WHERE id = :id",
            [':id' => $id]
        );
    }

    return $row ? $row['name'] : '-';
}
