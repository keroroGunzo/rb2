<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

try {

    $db->beginTransaction();

    // ambil semua movement transfer
    $rows = $db->query("
        SELECT *
        FROM stock_movements
        WHERE reference_id = :id
        AND movement_type = 'transfer'
    ", [':id' => $id]);

    foreach ($rows as $row) {

        // kembalikan stok asal
        $db->execute("
            UPDATE stocks
            SET qty = qty + :qty
            WHERE product_id = :pid
            AND location_type = :from_type
            AND location_id = :from_id
        ", [
            ':qty' => $row['qty'],
            ':pid' => $row['product_id'],
            ':from_type' => $row['from_type'],
            ':from_id' => $row['from_id']
        ]);

        // kurangi stok tujuan
        $db->execute("
            UPDATE stocks
            SET qty = qty - :qty
            WHERE product_id = :pid
            AND location_type = :to_type
            AND location_id = :to_id
        ", [
            ':qty' => $row['qty'],
            ':pid' => $row['product_id'],
            ':to_type' => $row['to_type'],
            ':to_id' => $row['to_id']
        ]);
    }

    // hapus movement
    $db->execute("
        DELETE FROM stock_movements
        WHERE reference_id = :id
        AND movement_type = 'transfer'
    ", [':id' => $id]);

    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Transfer berhasil dihapus.'
    ]);
} catch (Exception $e) {

    $db->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
