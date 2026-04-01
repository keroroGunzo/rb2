<?php
require '../config/init.php';

header('Content-Type: application/json');

$output = ['data' => []];

$rows = $db->query("
    SELECT e.id, DATE(e.expense_date) as expense_date, ec.name as category, e.description, e.amount,e.created_at, u.name as created_by
    FROM expenses e
    JOIN expense_categories ec ON e.category_id = ec.id
    JOIN users u ON e.created_by = u.id
    ORDER BY e.expense_date DESC
");

$no = 1;

foreach ($rows as $r) {
    $output['data'][] = [
        'no' => $no++,
        'id' => $r['id'],
        'date' => $r['expense_date'],
        'category' => $r['category'],
        'description' => $r['description'],
        'amount' => $r['amount'],
        'created_at' => $r['created_at'],
        'created_by' => $r['created_by']
    ];
}

echo json_encode($output);
