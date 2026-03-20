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
            id,sku,barcode,name,price_retail,price_wholesale,min_wholesale_qty,last_cost,avg_cost,created_at
        FROM products
        ORDER BY name ASC
    ";

    $rows = $db->query($sql);

    $no = 1;
    foreach ($rows as $row) {
        $output['data'][] = [
            'no'                => $no++,
            'id'                => $row['id'],
            'sku'               => $row['sku'],
            'barcode'           => $row['barcode'],
            'name'              => $row['name'],
            'price_retail'      => $row['price_retail'],
            'price_wholesale'   => $row['price_wholesale'],
            'min_wholesale_qty' => $row['min_wholesale_qty'],
            'last_cost'         => $row['last_cost'],
            'avg_cost'          => $row['avg_cost'],
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
