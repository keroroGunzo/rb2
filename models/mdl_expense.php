<?php
require '../config/init.php';

header('Content-Type: application/json');

$response = ['success' => false];

$id       = $_POST['id'] ?? null;
$date     = $_POST['expense_date'] ?? date('Y-m-d');
$category = $_POST['category'] ?? '';
$desc     = $_POST['description'] ?? '';
$amount   = floatval($_POST['amount'] ?? 0);

if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Amount tidak valid']);
    exit;
}

try {

    // ===============================
    // UPDATE MODE
    // ===============================
    if ($id && is_numeric($id)) {

        $db->execute("
            UPDATE expenses SET
                expense_date = :date,
                category_id  = :cat,
                description  = :desc,
                amount       = :amt,
                updated_at   = NOW()
            WHERE id = :id
        ", [
            ':date' => $date,
            ':cat'  => $category,
            ':desc' => $desc,
            ':amt'  => $amount,
            ':id'   => $id
        ]);
    }
    // ===============================
    // INSERT MODE
    // ===============================
    else {

        $db->execute("
            INSERT INTO expenses
            (expense_date, category_id, description, amount, created_by)
            VALUES
            (:date,:cat,:desc,:amt,:user)
        ", [
            ':date' => $date,
            ':cat'  => $category,
            ':desc' => $desc,
            ':amt'  => $amount,
            ':user' => $_SESSION['user_id']
        ]);
    }

    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
