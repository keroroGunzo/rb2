<?php
require '../config/init.php';
header('Content-Type: application/json');

$output = ['data' => []];
$start = $_GET['start'] ?? null;
$end   = $_GET['end'] ?? null;

$where = "";
$params = [];

if ($start && $end) {
    $where = "WHERE DATE(s.created_at) BETWEEN :start AND :end";
    $params[':start'] = $start;
    $params[':end']   = $end;
}

try {

    $sql = "
        SELECT
            p.id,
            p.name,

            COALESCE(SUM(si.qty),0) AS sold_qty,
            COALESCE(SUM(si.price * si.qty),0) AS gross_revenue,
            COALESCE(SUM(si.cost  * si.qty),0) AS gross_cogs,

            COALESCE(r.return_qty,0) AS return_qty,
            COALESCE(r.refund,0) AS refund,
            COALESCE(r.cogs_reverse,0) AS cogs_reverse,

            COALESCE(SUM(s.discount),0) AS total_discount

        FROM sale_items si
        JOIN products p ON p.id = si.product_id
        JOIN sales s ON s.id = si.sale_id
        $where

        LEFT JOIN (
            SELECT
                sri.product_id,
                SUM(sri.qty) AS return_qty,
                SUM(sri.price * sri.qty) AS refund,
                SUM(sri.cost  * sri.qty) AS cogs_reverse
            FROM sale_return_items sri
            GROUP BY sri.product_id
        ) r ON r.product_id = si.product_id

        GROUP BY p.id, p.name
        ORDER BY p.name
    ";

    $rows = $db->query($sql, $params);

    $no = 1;

    foreach ($rows as $row) {

        $gross_revenue = (float)$row['gross_revenue'];
        $gross_cogs    = (float)$row['gross_cogs'];
        $refund        = (float)$row['refund'];
        $cogs_reverse  = (float)$row['cogs_reverse'];
        $discount      = (float)$row['total_discount'];

        $ratio = $gross_revenue > 0
            ? (($gross_revenue - $discount) / $gross_revenue)
            : 1;

        $net_revenue = ($gross_revenue * $ratio) - $refund;
        $net_cogs    = $gross_cogs - $cogs_reverse;
        $profit      = $net_revenue - $net_cogs;

        $net_qty = (float)$row['sold_qty'] - (float)$row['return_qty'];

        // ⭐ MARGIN SAFE
        $margin = $net_revenue != 0
            ? ($profit / $net_revenue) * 100
            : 0;

        $output['data'][] = [
            'no'       => $no++,
            'product'  => $row['name'],
            'qty'      => $net_qty,
            'revenue'  => $net_revenue,
            'cogs'     => $net_cogs,
            'profit'   => $profit,
            'margin'   => $margin
        ];
    }
} catch (Exception $e) {
    // silent
}

echo json_encode($output);
