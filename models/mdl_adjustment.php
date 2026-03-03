<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

$type  = $_POST['location_type'] ?? '';
$locId = (int)($_POST['location_id'] ?? 0);
$productId = (int)($_POST['product_id'] ?? 0);
$adjType   = $_POST['adjustment_type'] ?? '';
$qty       = (int)($_POST['qty'] ?? 0);
$note      = trim($_POST['note'] ?? '');
$userId    = $_SESSION['user_id'];

if (!$type || !$locId || !$productId || !$adjType || !$qty) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {

    $db->beginTransaction();

    // 🔥 Hitung nilai adjustment
    $finalQty = ($adjType === 'add') ? $qty : -$qty;

    // 🔎 Cek stok jika subtract
    if ($adjType === 'subtract') {

        $stok = $db->single("
            SELECT qty
            FROM stocks
            WHERE product_id = :pid
            AND location_type = :type
            AND location_id = :loc
        ", [
            ':pid' => $productId,
            ':type' => $type,
            ':loc' => $locId
        ]);

        if (!$stok || $stok['qty'] < $qty) {
            throw new Exception("Stok tidak mencukupi.");
        }
    }

    // 🔥 Update stok
    $db->execute("
        INSERT INTO stocks (product_id, location_type, location_id, stock_status, qty)
        VALUES (:pid, :type, :loc, 'sellable', :qty)
        ON DUPLICATE KEY UPDATE qty = qty + :qty
    ", [
        ':pid' => $productId,
        ':type' => $type,
        ':loc' => $locId,
        ':qty' => $finalQty
    ]);

    // 🔥 Insert movement
    $db->execute("
    INSERT INTO stock_movements
    (product_id, to_type, to_id, qty, movement_type, reference_id, note, user_id)
    VALUES (:pid, :type, :loc, :qty, 'adjustment', NULL, :note, :uid)
", [
        ':pid' => $productId,
        ':type' => $type,
        ':loc' => $locId,
        ':qty' => $finalQty,
        ':note' => $note,
        ':uid' => $userId
    ]);

    $db->commit();

    $response['success'] = true;
    $response['message'] = "Adjustment berhasil disimpan.";
} catch (Exception $e) {

    $db->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
