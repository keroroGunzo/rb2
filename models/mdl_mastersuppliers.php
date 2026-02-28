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
$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');


// ================= VALIDASI =================
if ($name === '' || $address === '') {
    $response['message'] = "Nama & Alamat wajib diisi.";
    echo json_encode($response);
    exit;
}

try {

    // ================= UPDATE =================
    if ($id) {

        $sql = "
            UPDATE suppliers 
            SET name = :name,
                phone = :phone,
                address = :address               
            WHERE id = :id
        ";

        $params = [
            ':id'      => $id,
            ':name'    => $name,
            ':phone'   => $phone,
            ':address' => $address
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil diupdate.";

    } 
    // ================= INSERT =================
    else {

        $sql = "
            INSERT INTO suppliers (name, phone, address, created_at)
            VALUES (:name, :phone, :address, NOW())
        ";

        $params = [
            ':name'    => $name,
            ':phone'   => $phone,
            ':address' => $address
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil ditambahkan.";
    }

} catch (PDOException $e) {

    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);