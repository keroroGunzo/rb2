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
            sm.to_type,
            sm.to_id,
            sm.qty,
            sm.created_at,
            p.name AS product_name,
            p.sku
        FROM stock_movements sm
        JOIN products p ON p.id = sm.product_id
        WHERE sm.movement_type = 'adjustment'
        ORDER BY sm.created_at DESC
    ");

    $no = 1;

    foreach ($rows as $row) {

        // 🔥 Tentukan tipe adjustment
        $type = ($row['qty'] >= 0) ? 'Tambah' : 'Kurang';

        // 🔥 Ambil nama lokasi
        $locationName = getLocationName(
            $db,
            $row['to_type'],
            $row['to_id']
        );

        $output['data'][] = [
            'no'           => $no++,
            'id'           => $row['id'],
            'location'     => $locationName,
            'product'      => $row['sku'] . ' - ' . $row['product_name'],
            'type'         => $type,
            'qty'          => abs($row['qty']),
            'created_at'   => $row['created_at']
        ];
    }
} catch (Exception $e) {

    http_response_code(500);

    $output = [
        'error'   => true,
        'message' => $e->getMessage()
    ];
}

echo json_encode($output);


// =========================
// 🔧 Helper Ambil Nama Lokasi
// =========================
function getLocationName($db, $type, $id)
{
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
