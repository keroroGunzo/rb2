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
            id,name,address,created_at
        FROM warehouses
        ORDER BY name ASC
    ";

    $rows = $db->query($sql);

    $no = 1;
    foreach ($rows as $row) {
        $output['data'][] = [
            'no'          => $no++,
            'id'          => $row['id'],
            'name'        => $row['name'],
            'address'     => $row['address'],
            'created_at'  => $row['created_at']
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
