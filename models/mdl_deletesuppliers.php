<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$ID = $_POST['ID'] ?? null;

if (!$ID || !is_numeric($ID)) {
    $response['message'] = "ID tidak valid.";
    echo json_encode($response);
    exit;
}

$sql = "DELETE FROM suppliers WHERE id = :id";

try {
    if ($db->execute($sql, [':id' => $ID])) {
        $response['success'] = true;
        $response['message'] = "Data berhasil dihapus.";
    } else {
        $response['message'] = "Gagal menghapus data.";
    }
} catch (PDOException $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

echo json_encode($response);
