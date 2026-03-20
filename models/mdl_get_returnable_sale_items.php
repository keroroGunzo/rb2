<?php
require '../config/init.php';

header('Content-Type: application/json');

$sale_id = $_GET['sale_id'] ?? 0;

try {

    $rows = $db->query("
    SELECT
      si.id AS sale_item_id,
      si.product_id,
      p.name,
      si.qty AS sold_qty,
      COALESCE(SUM(sri.qty),0) AS returned_qty,
      (si.qty - COALESCE(SUM(sri.qty),0)) AS remaining_qty
    FROM sale_items si
    JOIN products p ON p.id = si.product_id
    LEFT JOIN sale_return_items sri
      ON sri.sale_item_id = si.id
    WHERE si.sale_id = :sale_id
    GROUP BY si.id, si.product_id, p.name, si.qty
    HAVING remaining_qty > 0
  ", [':sale_id' => $sale_id]);

    echo json_encode(["success" => true, "data" => $rows]);
} catch (Exception $e) {

    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
