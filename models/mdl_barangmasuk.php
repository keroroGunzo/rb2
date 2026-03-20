<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

$id            = (int)($_POST['id'] ?? 0);
$supplier_id   = (int)$_POST['supplier_id'];
$warehouse_id  = (int)$_POST['warehouse_id'];
$items         = $_POST['items'] ?? [];
$total         = (float)$_POST['total'];

if (!$supplier_id || !$warehouse_id || empty($items)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {

    $db->beginTransaction();

    // ==============================
    // UPDATE MODE
    // ==============================
    if ($id) {

        $oldItems = $db->query("
            SELECT product_id, qty
            FROM purchase_items
            WHERE purchase_id = :id
        ", [':id' => $id]);

        foreach ($oldItems as $old) {
            $db->execute("
                UPDATE stocks
                SET qty = qty - :qty
                WHERE product_id = :product_id
                AND location_type = 'warehouse'
                AND location_id = :warehouse_id
            ", [
                ':qty' => $old['qty'],
                ':product_id' => $old['product_id'],
                ':warehouse_id' => $warehouse_id
            ]);
        }

        $db->execute("DELETE FROM purchase_items WHERE purchase_id = :id", [':id' => $id]);

        $db->execute("
            UPDATE purchases
            SET supplier_id = :supplier_id,
                warehouse_id = :warehouse_id,
                total = :total
            WHERE id = :id
        ", [
            ':supplier_id' => $supplier_id,
            ':warehouse_id' => $warehouse_id,
            ':total'       => $total,
            ':id'          => $id
        ]);

        $purchase_id = $id;

    } else {

        $db->execute("
            INSERT INTO purchases (supplier_id, warehouse_id, total)
            VALUES (:supplier_id, :warehouse_id, :total)
        ", [
            ':supplier_id'  => $supplier_id,
            ':warehouse_id' => $warehouse_id,
            ':total'        => $total
        ]);

        $purchase_id = $db->lastInsertId();
    }

    // ==============================
    // INSERT ITEMS + STOCK + COST
    // ==============================
    foreach ($items as $item) {

        $product_id = (int)$item['product_id'];
        $qty        = (int)$item['qty'];
        $cost       = (float)$item['cost_price'];
        $subtotal   = $qty * $cost;

        // insert purchase item
        $db->execute("
            INSERT INTO purchase_items
            (purchase_id, product_id, qty, cost_price, subtotal)
            VALUES (:purchase_id, :product_id, :qty, :cost_price, :subtotal)
        ", [
            ':purchase_id' => $purchase_id,
            ':product_id'  => $product_id,
            ':qty'         => $qty,
            ':cost_price'  => $cost,
            ':subtotal'    => $subtotal
        ]);

        // ambil stok lama
        $stockRow = $db->single("
            SELECT qty
            FROM stocks
            WHERE product_id = :product_id
            AND location_type = 'warehouse'
            AND location_id = :warehouse_id
        ", [
            ':product_id' => $product_id,
            ':warehouse_id' => $warehouse_id
        ]);

        $stock_old = $stockRow ? (float)$stockRow['qty'] : 0;

        // update stock
        $db->execute("
            INSERT INTO stocks
            (product_id, location_type, location_id, stock_status, qty)
            VALUES (:product_id, 'warehouse', :warehouse_id, 'sellable', :qty)
            ON DUPLICATE KEY UPDATE qty = qty + :qty
        ", [
            ':product_id'   => $product_id,
            ':warehouse_id' => $warehouse_id,
            ':qty'          => $qty
        ]);

        // movement
        $db->execute("
            INSERT INTO stock_movements
            (product_id, to_type, to_id, qty, movement_type, reference_id)
            VALUES (:product_id, 'warehouse', :warehouse_id, :qty, 'purchase', :ref)
        ", [
            ':product_id'   => $product_id,
            ':warehouse_id' => $warehouse_id,
            ':qty'          => $qty,
            ':ref'          => $purchase_id
        ]);

        // ================= COST ENGINE =================

        $product = $db->single("
            SELECT avg_cost
            FROM products
            WHERE id = :id
        ", [':id' => $product_id]);

        $avg_old = $product ? (float)$product['avg_cost'] : 0;

        $avg_new = (($stock_old * $avg_old) + ($qty * $cost))
                  / ($stock_old + $qty);

        $db->execute("
            UPDATE products
            SET last_cost = :last_cost,
                avg_cost   = :avg_cost
            WHERE id = :id
        ", [
            ':last_cost' => $cost,
            ':avg_cost'  => $avg_new,
            ':id'        => $product_id
        ]);
    }

    $db->commit();

    $response['success'] = true;
    $response['message'] = $id
        ? "Barang masuk berhasil diupdate."
        : "Barang masuk berhasil ditambahkan.";

} catch (Exception $e) {

    $db->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);