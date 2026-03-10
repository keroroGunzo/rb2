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
            p.id, s.name AS supplier_name, w.name AS warehouse_name, p.total, p.created_at, p.invoice_no
        FROM purchases p
        LEFT JOIN suppliers s ON p.supplier_id = s.id
        LEFT JOIN warehouses w ON p.warehouse_id = w.id
    ";

    $rows = $db->query($sql);

    $no = 1;
    foreach ($rows as $row) {
        $output['data'][] = [
            'no'          => $no++,
            'id'          => $row['id'],
            'supplier_name' => $row['supplier_name'],
            'warehouse_name' => $row['warehouse_name'],
            'total'       => $row['total'],
            'created_at'  => date('d M Y H:i:s', strtotime($row['created_at'])),
            'invoice_no'  => $row['invoice_no']
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
