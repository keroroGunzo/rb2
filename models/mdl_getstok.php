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
            s.id,
            p.sku,
            p.name AS product_name,
            s.location_type,
            CASE 
                WHEN s.location_type = 'warehouse' THEN w.name
                WHEN s.location_type = 'store' THEN st.name
            END AS location_name,
            s.stock_status,
            s.qty,
            s.updated_at
        FROM stocks s
        JOIN products p ON p.id = s.product_id
        LEFT JOIN warehouses w 
            ON w.id = s.location_id AND s.location_type = 'warehouse'
        LEFT JOIN stores st
            ON st.id = s.location_id AND s.location_type = 'store'
        WHERE s.qty > 0
        ORDER BY p.name ASC
    ");

    $no = 1;

    foreach ($rows as $row) {
        $output['data'][] = [
            'no'            => $no++,
            'id'            => $row['id'],
            'sku'           => $row['sku'],
            'product_name'  => $row['product_name'],
            'location_type' => ucfirst($row['location_type']),
            'location_name' => $row['location_name'],
            'stock_status'  => ucfirst($row['stock_status']),
            'qty'           => number_format($row['qty']),
            'updated_at'    => date('d M Y H:i:s', strtotime($row['updated_at'])),
        ];
    }

} catch (Exception $e) {
    http_response_code(500);
    $output = [
        'error' => true,
        'message' => $e->getMessage()
    ];
}

echo json_encode($output);