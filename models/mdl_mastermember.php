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

$id       = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$discount_percent = trim($_POST['discount_percent'] ?? '');

// ================= VALIDASI =================
if ($name === '' || $phone === '' || $discount_percent === '') {
    $response['message'] = "Semua field wajib diisi.";
    echo json_encode($response);
    exit;
}

try {

    // ================= UPDATE =================
    if ($id) {

        $sql = "
            UPDATE members SET 
                name = :name,
                phone = :phone,
                discount_percent = :discount_percent
            WHERE id = :id
        ";
        $params = [
            ':id'      => $id,
            ':name'    => $name,
            ':phone'   => $phone,
            ':discount_percent' => $discount_percent
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil diupdate.";

    } 
    // ================= INSERT =================
    else {

        $sql = "
            INSERT INTO members (name, phone, discount_percent, created_at)
            VALUES (:name, :phone, :discount_percent, NOW())
        ";

        $params = [
            ':name' => $name,
            ':phone' => $phone,
            ':discount_percent' => $discount_percent
            
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "Data berhasil ditambahkan.";
    }

} catch (PDOException $e) {

    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);