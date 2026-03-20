<?php
require '../config/init.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$sale_id = $data['sale_id'];
$items = $data['items'];

try {

    $db->beginTransaction();

    $sale = $db->single("
    SELECT store_id
    FROM sales
    WHERE id = :id
  ", [':id' => $sale_id]);

    $db->execute("
    INSERT INTO sale_returns
    (sale_id, store_id, total_refund, created_by)
    VALUES (:s,:st,0,:u)
  ", [
        ':s' => $sale_id,
        ':st' => $sale['store_id'],
        ':u' => $_SESSION['user_id']
    ]);

    $return_id = $db->lastInsertId();
    $total = 0;

    foreach ($items as $it) {

        $si = $db->single("
      SELECT product_id, price, cost
      FROM sale_items
      WHERE id = :id
    ", [':id' => $it['sale_item_id']]);

        $subtotal = $si['price'] * $it['qty'];
        $total += $subtotal;

        $db->execute("
      INSERT INTO sale_return_items
      (sale_return_id, sale_item_id, product_id, qty, price, cost, subtotal)
      VALUES (:h,:si,:p,:q,:pr,:c,:s)
    ", [
            ':h' => $return_id,
            ':si' => $it['sale_item_id'],
            ':p' => $si['product_id'],
            ':q' => $it['qty'],
            ':pr' => $si['price'],
            ':c' => $si['cost'],
            ':s' => $subtotal
        ]);

        // STOCK MASUK KE STORE
        $db->execute("
      UPDATE stocks
      SET qty = qty + :qty
      WHERE product_id = :p
      AND location_type='store'
      AND location_id=:st
    ", [
            ':qty' => $it['qty'],
            ':p' => $si['product_id'],
            ':st' => $sale['store_id']
        ]);

        // MOVEMENT
        $db->execute("
      INSERT INTO stock_movements
      (product_id,from_type,from_id,qty,movement_type,reference_id)
      VALUES (:p,'store',:st,:q,'sale_return',:ref)
    ", [
            ':p' => $si['product_id'],
            ':st' => $sale['store_id'],
            ':q' => $it['qty'],
            ':ref' => $return_id
        ]);
    }

    $db->execute("
    UPDATE sale_returns
    SET total_refund=:t
    WHERE id=:id
  ", [
        ':t' => $total,
        ':id' => $return_id
    ]);

    $db->commit();

    echo json_encode(["success" => true]);
} catch (Exception $e) {

    $db->rollBack();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
