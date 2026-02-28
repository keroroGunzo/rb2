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

$id        = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;
$name      = trim($_POST['name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = trim($_POST['password'] ?? '');
$role      = trim($_POST['role'] ?? '');
$store_id  = isset($_POST['store_id']) && is_numeric($_POST['store_id']) ? (int)$_POST['store_id'] : null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;


// ================= VALIDASI =================
if ($name === '' || $email === '' || $role === '') {
    $response['message'] = "Nama, Email, dan Role wajib diisi.";
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = "Format email tidak valid.";
    echo json_encode($response);
    exit;
}

try {

    // ================= UPDATE =================
    if ($id) {

        // Cek email unik kecuali diri sendiri
        $check = $db->single(
            "SELECT id FROM users WHERE email = :email AND id != :id",
            [':email' => $email, ':id' => $id]
        );

        if ($check) {
            $response['message'] = "Email sudah digunakan.";
            echo json_encode($response);
            exit;
        }

        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "
                UPDATE users
                SET name = :name,
                    email = :email,
                    password = :password,
                    role = :role,
                    store_id = :store_id,
                    is_active = :is_active
                WHERE id = :id
            ";

            $params = [
                ':id'        => $id,
                ':name'      => $name,
                ':email'     => $email,
                ':password'  => $hashedPassword,
                ':role'      => $role,
                ':store_id'  => $store_id,
                ':is_active' => $is_active
            ];

        } else {

            $sql = "
                UPDATE users
                SET name = :name,
                    email = :email,
                    role = :role,
                    store_id = :store_id,
                    is_active = :is_active
                WHERE id = :id
            ";

            $params = [
                ':id'        => $id,
                ':name'      => $name,
                ':email'     => $email,
                ':role'      => $role,
                ':store_id'  => $store_id,
                ':is_active' => $is_active
            ];
        }

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "User berhasil diupdate.";
    }

    // ================= INSERT =================
    else {

        if ($password === '') {
            $response['message'] = "Password wajib diisi.";
            echo json_encode($response);
            exit;
        }

        $check = $db->single(
            "SELECT id FROM users WHERE email = :email",
            [':email' => $email]
        );

        if ($check) {
            $response['message'] = "Email sudah digunakan.";
            echo json_encode($response);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO users (name, email, password, role, store_id, is_active, created_at)
            VALUES (:name, :email, :password, :role, :store_id, :is_active, NOW())
        ";

        $params = [
            ':name'      => $name,
            ':email'     => $email,
            ':password'  => $hashedPassword,
            ':role'      => $role,
            ':store_id'  => $store_id,
            ':is_active' => $is_active
        ];

        $db->execute($sql, $params);

        $response['success'] = true;
        $response['message'] = "User berhasil ditambahkan.";
    }

} catch (PDOException $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);