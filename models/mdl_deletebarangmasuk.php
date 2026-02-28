<?php
require '../config/init.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

$id = isset($_POST['ID']) && is_numeric($_POST['ID'])
    ? (int)$_POST['ID']
    : 0;

if (!$id) {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak valid.'
    ]);
    exit;
}

try {

    $db->beginTransaction();

    // ============================
    // 1️⃣ Ambil header
    // ============================
    $header = $db->single("
        SELECT warehouse_id
        FROM purchases
        WHERE id = :id
    ", [':id' => $id]);

    if (!$header) {
        throw new Exception("Data tidak ditemukan.");
    }

    $warehouse_id = $header['warehouse_id'];

    // ============================
    // 2️⃣ Ambil item lama
    // ============================
    $items = $db->query("
        SELECT product_id, qty
        FROM purchase_items
        WHERE purchase_id = :id
    ", [':id' => $id]);

    // ============================
    // 3️⃣ Kurangi stok
    // ============================
    foreach ($items as $item) {

        $db->execute("
            UPDATE stocks
            SET qty = qty - :qty
            WHERE product_id = :product_id
            AND location_type = 'warehouse'
            AND location_id = :warehouse_id
        ", [
            ':qty' => $item['qty'],
            ':product_id' => $item['product_id'],
            ':warehouse_id' => $warehouse_id
        ]);

        // optional: catat movement reversal
        $db->execute("
            INSERT INTO stock_movements
            (product_id, from_type, from_id, qty, movement_type, reference_id)
            VALUES (:product_id, 'warehouse', :warehouse_id, :qty, 'adjustment', :ref)
        ", [
            ':product_id' => $item['product_id'],
            ':warehouse_id' => $warehouse_id,
            ':qty' => $item['qty'],
            ':ref' => $id
        ]);
    }

    // ============================
    // 4️⃣ Hapus detail
    // ============================
    $db->execute("
        DELETE FROM purchase_items
        WHERE purchase_id = :id
    ", [':id' => $id]);

    // ============================
    // 5️⃣ Hapus header
    // ============================
    $db->execute("
        DELETE FROM purchases
        WHERE id = :id
    ", [':id' => $id]);

    $db->commit();

    $response['success'] = true;
    $response['message'] = "Barang masuk berhasil dihapus & stok dikembalikan.";

} catch (Exception $e) {

    $db->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);