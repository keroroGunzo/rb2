<?php
require '../config/init.php';

header('Content-Type: application/json');

$response = ['success' => false];

$from_type = $_POST['from_type'];
$from_id   = (int)$_POST['from_id'];
$to_type   = $_POST['to_type'];
$to_id     = (int)$_POST['to_id'];
$items     = $_POST['items'];

if (!$from_type || !$from_id || !$to_type || !$to_id || empty($items)) {
    echo json_encode(['success'=>false,'message'=>'Data tidak lengkap']);
    exit;
}

try {

    $db->beginTransaction();

    // insert header
    $db->execute("
        INSERT INTO transfers (from_type, from_id, to_type, to_id, total_items)
        VALUES (:from_type, :from_id, :to_type, :to_id, :total)
    ", [
        ':from_type'=>$from_type,
        ':from_id'=>$from_id,
        ':to_type'=>$to_type,
        ':to_id'=>$to_id,
        ':total'=>count($items)
    ]);

    $transfer_id = $db->lastInsertId();

    foreach ($items as $item) {

        $product_id = (int)$item['product_id'];
        $qty        = (int)$item['qty'];

        // cek stok cukup
        $stok = $db->single("
            SELECT qty FROM stocks
            WHERE product_id=:product_id
            AND location_type=:type
            AND location_id=:id
        ", [
            ':product_id'=>$product_id,
            ':type'=>$from_type,
            ':id'=>$from_id
        ]);

        if (!$stok || $stok['qty'] < $qty) {
            throw new Exception("Stok tidak cukup.");
        }

        // kurangi asal
        $db->execute("
            UPDATE stocks
            SET qty = qty - :qty
            WHERE product_id=:product_id
            AND location_type=:type
            AND location_id=:id
        ", [
            ':qty'=>$qty,
            ':product_id'=>$product_id,
            ':type'=>$from_type,
            ':id'=>$from_id
        ]);

        // tambah tujuan
        $db->execute("
            INSERT INTO stocks
            (product_id, location_type, location_id, stock_status, qty)
            VALUES (:product_id, :type, :id, 'sellable', :qty)
            ON DUPLICATE KEY UPDATE qty = qty + :qty
        ", [
            ':product_id'=>$product_id,
            ':type'=>$to_type,
            ':id'=>$to_id,
            ':qty'=>$qty
        ]);

        // insert detail
        $db->execute("
            INSERT INTO transfer_items
            (transfer_id, product_id, qty)
            VALUES (:transfer_id, :product_id, :qty)
        ", [
            ':transfer_id'=>$transfer_id,
            ':product_id'=>$product_id,
            ':qty'=>$qty
        ]);

        // movement log
        $db->execute("
            INSERT INTO stock_movements
            (product_id, from_type, from_id, to_type, to_id, qty, movement_type, reference_id)
            VALUES (:product_id, :from_type, :from_id, :to_type, :to_id, :qty, 'transfer', :ref)
        ", [
            ':product_id'=>$product_id,
            ':from_type'=>$from_type,
            ':from_id'=>$from_id,
            ':to_type'=>$to_type,
            ':to_id'=>$to_id,
            ':qty'=>$qty,
            ':ref'=>$transfer_id
        ]);
    }

    $db->commit();

    $response['success'] = true;
    $response['message'] = "Transfer berhasil.";

} catch (Exception $e) {

    $db->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);