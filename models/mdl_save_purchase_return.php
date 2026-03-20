<?php
require '../config/init.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$purchase_id = $data['purchase_id'] ?? 0;
$items = $data['items'] ?? [];

if (!$purchase_id || empty($items)) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

try {

    $db->beginTransaction();

    $p = $db->single("
        SELECT supplier_id, warehouse_id
        FROM purchases
        WHERE id = :id
    ", [':id' => $purchase_id]);

    if (!$p) throw new Exception("Purchase tidak ditemukan");

    $db->execute("
        INSERT INTO purchase_returns
        (purchase_id, supplier_id, warehouse_id, total, created_by)
        VALUES (:p,:s,:w,0,:u)
    ", [
        ':p' => $purchase_id,
        ':s' => $p['supplier_id'],
        ':w' => $p['warehouse_id'],
        ':u' => $_SESSION['user_id']
    ]);

    $return_id = $db->lastInsertId();

    $total = 0;

    foreach ($items as $it) {

        $pi_id = $it['purchase_item_id'];
        $qty = $it['qty'];

        $row = $db->single("
            SELECT product_id, cost_price
            FROM purchase_items
            WHERE id = :id
        ", [':id' => $pi_id]);

        $product_id = $row['product_id'];
        $cost = $row['cost_price'];
        $subtotal = $qty * $cost;
        $total += $subtotal;

        $db->execute("
            INSERT INTO purchase_return_items
            (purchase_return_id,purchase_item_id,product_id,qty,cost_price,subtotal)
            VALUES (:h,:pi,:p,:q,:c,:s)
        ", [
            ':h' => $return_id,
            ':pi' => $pi_id,
            ':p' => $product_id,
            ':q' => $qty,
            ':c' => $cost,
            ':s' => $subtotal
        ]);

        // UPDATE STOCK
        $db->execute("
            UPDATE stocks
            SET qty = qty - :qty
            WHERE product_id = :product
            AND location_type='warehouse'
            AND location_id=:warehouse
        ", [
            ':qty' => $qty,
            ':product' => $product_id,
            ':warehouse' => $p['warehouse_id']
        ]);

        // MOVEMENT
        $db->execute("
            INSERT INTO stock_movements
            (product_id,from_type,from_id,qty,movement_type,reference_id)
            VALUES (:p,'warehouse',:w,:q,'purchase_return',:ref)
        ", [
            ':p' => $product_id,
            ':w' => $p['warehouse_id'],
            ':q' => $qty,
            ':ref' => $return_id
        ]);

        // REVERSE AVG COST
        $stock = $db->single("
            SELECT qty FROM stocks
            WHERE product_id=:p
            AND location_type='warehouse'
            AND location_id=:w
        ", [
            ':p' => $product_id,
            ':w' => $p['warehouse_id']
        ]);

        $stock_old = $stock['qty'] + $qty;

        $prod = $db->single("
            SELECT avg_cost
            FROM products
            WHERE id=:id
        ", [':id' => $product_id]);

        $avg_old = $prod['avg_cost'];

        $stock_new = $stock_old - $qty;

        if ($stock_new <= 0) {
            $avg_new = $cost;
        } else {
            $avg_new = (($stock_old * $avg_old) - ($qty * $cost)) / $stock_new;
        }

        $db->execute("
    UPDATE products
    SET avg_cost = :avg
    WHERE id = :id
", [
            ':avg' => $avg_new,
            ':id' => $product_id
        ]);
    }

    $db->execute("
        UPDATE purchase_returns
        SET total=:t
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
