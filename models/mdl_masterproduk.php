<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

$id      = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;
$sku     = trim($_POST['sku'] ?? '');
$barcode = trim($_POST['barcode'] ?? '');
$name    = trim($_POST['name'] ?? '');
$price_retail = floatval($_POST['price_retail'] ?? 0);
$price_wholesale = floatval($_POST['price_wholesale'] ?? 0);
$min_wholesale_qty = intval($_POST['min_wholesale_qty'] ?? 0);

if ($sku === '' || $barcode === '' || $name === '' || $price_retail <= 0) {
    $response['message'] = "Field wajib belum lengkap.";
    echo json_encode($response);
    exit;
}

try {

    if ($id) {

        $sql = "
            UPDATE products SET 
                sku = :sku,
                barcode = :barcode,
                name = :name,
                price_retail = :price_retail,
                price_wholesale = :price_wholesale,
                min_wholesale_qty = :min_wholesale_qty
            WHERE id = :id
        ";

        $params = [
            ':id' => $id,
            ':sku' => $sku,
            ':barcode' => $barcode,
            ':name' => $name,
            ':price_retail' => $price_retail,
            ':price_wholesale' => $price_wholesale,
            ':min_wholesale_qty' => $min_wholesale_qty
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Produk berhasil diupdate.";

    } else {

        $sql = "
            INSERT INTO products 
            (sku, barcode, name, price_retail, price_wholesale, min_wholesale_qty, last_cost, avg_cost)
            VALUES
            (:sku, :barcode, :name, :price_retail, :price_wholesale, :min_wholesale_qty, 0, 0)
        ";

        $params = [
            ':sku' => $sku,
            ':barcode' => $barcode,
            ':name' => $name,
            ':price_retail' => $price_retail,
            ':price_wholesale' => $price_wholesale,
            ':min_wholesale_qty' => $min_wholesale_qty
        ];  

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Produk berhasil ditambahkan.";
    }

} catch (PDOException $e) {

    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);