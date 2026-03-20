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
  p.id,
  s.name AS supplier_name,
  w.name AS warehouse_name,
  p.total,
  p.invoice_no,
  p.created_at,

  CASE
    WHEN SUM(pi.qty - COALESCE(pr.returned_qty,0)) = 0 THEN 'FULL RETURN'
    WHEN SUM(COALESCE(pr.returned_qty,0)) > 0 THEN 'PARTIAL RETURN'
    ELSE 'OPEN'
  END AS return_status

FROM purchases p
JOIN suppliers s ON s.id = p.supplier_id
JOIN warehouses w ON w.id = p.warehouse_id
JOIN purchase_items pi ON pi.purchase_id = p.id

LEFT JOIN (
    SELECT purchase_item_id, SUM(qty) AS returned_qty
    FROM purchase_return_items
    GROUP BY purchase_item_id
) pr ON pr.purchase_item_id = pi.id

GROUP BY
  p.id, s.name, w.name, p.total, p.invoice_no, p.created_at
ORDER BY p.created_at DESC
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
            'invoice_no'  => $row['invoice_no'],
            'return_status' => $row['return_status'] 
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
