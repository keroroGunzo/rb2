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
$sku    = trim($_POST['sku'] ?? '');
$barcode = trim($_POST['barcode'] ?? '');
$nama_produk = trim($_POST['nama_produk'] ?? '');
$harga_retail = trim($_POST['harga_retail'] ?? '');
$harga_grosir = trim($_POST['harga_grosir'] ?? '');
$jumlah_minimal_grosir = trim($_POST['jumlah_minimal_grosir'] ?? '');
$harga_beli = trim($_POST['harga_beli'] ?? '');

// ================= VALIDASI =================
if ($sku === '' || $barcode === '' || $nama_produk === '' || $harga_retail === '' || $harga_grosir === '' || $jumlah_minimal_grosir === '') {
    $response['message'] = "Semua field wajib diisi.";
    echo json_encode($response);
    exit;
}

try {

    // ================= UPDATE =================
    if ($id) {

        $sql = "
            UPDATE products SET 
                sku = :sku,
                barcode = :barcode,
                name = :nama_produk,
                price_retail = :harga_retail,
                price_wholesale = :harga_grosir,
                min_wholesale_qty = :jumlah_minimal_grosir,
                cost_price = :harga_beli
            WHERE id = :id
        ";
        $params = [
            ':id'      => $id,
            ':sku'     => $sku,
            ':barcode' => $barcode,
            ':nama_produk' => $nama_produk,
            ':harga_retail' => $harga_retail,
            ':harga_grosir' => $harga_grosir,
            ':jumlah_minimal_grosir' => $jumlah_minimal_grosir
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil diupdate.";

    } 
    // ================= INSERT =================
    else {

        $sql = "
            INSERT INTO products (sku, barcode, name, price_retail, price_wholesale, min_wholesale_qty, cost_price, created_at)
            VALUES (:sku, :barcode, :nama_produk, :harga_retail, :harga_grosir, :jumlah_minimal_grosir, :harga_beli, NOW())
        ";

        $params = [
            ':sku'     => $sku,
            ':barcode' => $barcode,
            ':nama_produk' => $nama_produk,
            ':harga_retail' => $harga_retail,
            ':harga_grosir' => $harga_grosir,
            ':jumlah_minimal_grosir' => $jumlah_minimal_grosir,
            ':harga_beli' => $harga_beli
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil ditambahkan.";
    }

} catch (PDOException $e) {

    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);