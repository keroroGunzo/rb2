<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$output = ['data' => []];

try {

    $sql = "
    SELECT 
    sm.reference_id AS transfer_id,
    MIN(sm.from_type) as from_type,
    MIN(sm.from_id) as from_id,
    MIN(sm.to_type) as to_type,
    MIN(sm.to_id) as to_id,
    COUNT(*) as total_item,
    SUM(sm.qty) as total_qty,
    MAX(sm.created_at) as created_at
FROM stock_movements sm
WHERE sm.movement_type = 'transfer'
GROUP BY sm.reference_id
ORDER BY MAX(sm.created_at) DESC
";

    $rows = $db->query($sql);

    $no = 1;

    foreach ($rows as $row) {

        // 🔥 Ambil nama lokasi asal
        $fromName = getLocationName($db, $row['from_type'], $row['from_id']);

        // 🔥 Ambil nama lokasi tujuan
        $toName = getLocationName($db, $row['to_type'], $row['to_id']);

        $output['data'][] = [
            'no'          => $no++,
            'id'          => $row['transfer_id'],
            'from_name'   => $fromName,
            'to_name'     => $toName,
            'total_item'  => $row['total_item'],
            'total_qty'   => $row['total_qty'],
            'created_at'  => date('d M Y H:i:s', strtotime($row['created_at'])),
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
        $row = $db->single("SELECT name FROM warehouses WHERE id = :id", [':id' => $id]);
    } else {
        $row = $db->single("SELECT name FROM stores WHERE id = :id", [':id' => $id]);
    }

    return $row ? $row['name'] : '-';
}
