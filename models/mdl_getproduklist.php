<?php
require '../config/init.php';

header('Content-Type: application/json');

$output = ['data' => []];

$rows = $db->query("
SELECT 
p.id,
p.sku,
p.name,
p.price_retail,
s.location_type
FROM products p
LEFT JOIN stocks s ON p.id = s.product_id
WHERE s.location_type = 'store' and s.qty > 0
ORDER BY p.name
");

foreach ($rows as $r) {

    $output['data'][] = $r;
}

echo json_encode($output);
