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
            p.id,p.purchase_id, s.name AS supplier_name, w.name AS warehouse_name, p.return_date, p.total, p.note, u.name AS created_by, p.created_at
        FROM purchase_returns p
        LEFT JOIN suppliers s ON p.supplier_id = s.id
        LEFT JOIN warehouses w ON p.warehouse_id = w.id
        LEFT JOIN users u ON p.created_by = u.id
        ORDER BY p.created_at DESC
    ";

    $rows = $db->query($sql);

    $no = 1;
    foreach ($rows as $row) {
        $output['data'][] = [
            'no'                => $no++,
            'id'                => $row['id'],
            'purchase_id'       => $row['purchase_id'],
            'supplier_name'     => $row['supplier_name'],
            'warehouse_name'    => $row['warehouse_name'],
            'return_date'       => $row['return_date'],
            'total'             => $row['total'],
            'note'              => $row['note'],
            'created_by'        => $row['created_by'],
            'created_at'        => $row['created_at']
        ];
    }
} catch (PDOException $e) {
    http_response_code(500);
    $output = [
        'error'   => true,
        'message' => $e->getMessage()
    ];
}

echo json_encode($output);
