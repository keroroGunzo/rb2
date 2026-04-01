<?php
require '../config/init.php';

header('Content-Type: application/json');

$output = ['data' => []];

$rows = $db->query("
    SELECT id,name FROM expense_categories
    ORDER BY name ASC
");


foreach ($rows as $r) {
    $output['data'][] = [
        'id' => $r['id'],
        'name' => $r['name']
    ];
}

echo json_encode($output);
