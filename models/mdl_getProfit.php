<?php
require '../config/init.php';

header('Content-Type: application/json');

$output = ['data' => []];

try {

    $sql = "
        SELECT
            s.id,
            s.invoice_no,
            s.created_at,
            st.name AS store_name,
            u.name AS cashier_name,

            COALESCE(SUM(si.price * si.qty),0) AS revenue,
            COALESCE(SUM(si.cost * si.qty),0) AS cogs,
            COALESCE(s.discount,0) AS discount,

            COALESCE(r.refund,0) AS refund,
            COALESCE(r.cogs_reverse,0) AS cogs_reverse

        FROM sales s
        JOIN stores st ON st.id = s.store_id
        JOIN users u ON u.id = s.cashier_id
        JOIN sale_items si ON si.sale_id = s.id

        LEFT JOIN (
            SELECT
                sr.sale_id,
                SUM(sri.price * sri.qty) AS refund,
                SUM(sri.cost * sri.qty) AS cogs_reverse
            FROM sale_returns sr
            JOIN sale_return_items sri
                ON sri.sale_return_id = sr.id
            GROUP BY sr.sale_id
        ) r ON r.sale_id = s.id

        GROUP BY
            s.id,
            s.invoice_no,
            s.created_at,
            st.name,
            u.name,
            s.discount,
            r.refund,
            r.cogs_reverse

        ORDER BY s.created_at DESC
    ";

    $rows = $db->query($sql);

    $no = 1;

    foreach ($rows as $row) {

        $revenue = (float)$row['revenue'];
        $cogs = (float)$row['cogs'];
        $discount = (float)$row['discount'];
        $refund = (float)$row['refund'];
        $cogs_reverse = (float)$row['cogs_reverse'];

        /* ===============================
           NET CALCULATION
        =============================== */

        $net_revenue = $revenue - $discount - $refund;
        $net_cogs = $cogs - $cogs_reverse;
        $profit = $net_revenue - $net_cogs;

        $output['data'][] = [
            'no' => $no++,
            'invoice_no' => $row['invoice_no'],
            'date' => date('d M Y H:i', strtotime($row['created_at'])),
            'store' => $row['store_name'],
            'cashier' => $row['cashier_name'],
            'revenue' => $net_revenue,
            'cogs' => $net_cogs,
            'profit' => $profit
        ];
    }
} catch (Exception $e) {
    // silent fail
}

echo json_encode($output);
