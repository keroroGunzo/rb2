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
            s.total,
            s.discount,
            s.grand_total,
            s.payment_method,

            SUM(si.qty) AS sold_qty,
            COALESCE(r.returned_qty,0) AS returned_qty,

            CASE
                WHEN SUM(si.qty - COALESCE(r.returned_qty,0)) = 0 THEN 'FULL RETURN'
                WHEN COALESCE(r.returned_qty,0) > 0 THEN 'PARTIAL RETURN'
                ELSE 'OPEN'
            END AS return_status

        FROM sales s
        JOIN stores st ON st.id = s.store_id
        JOIN users u ON u.id = s.cashier_id
        JOIN sale_items si ON si.sale_id = s.id

        LEFT JOIN (
            SELECT
                sr.sale_id,
                SUM(sri.qty) AS returned_qty
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
            s.total,
            s.discount,
            s.grand_total,
            s.payment_method,
            r.returned_qty

        ORDER BY s.created_at DESC
    ";

    $rows = $db->query($sql);

    $no = 1;

    foreach ($rows as $row) {

        $output['data'][] = [
            'no'             => $no++,
            'id'             => $row['id'],
            'invoice_no'     => $row['invoice_no'],
            'created_at'     => date('d M Y H:i', strtotime($row['created_at'])),
            'store_name'     => $row['store_name'],
            'cashier_name'   => $row['cashier_name'],
            'total'          => $row['total'],
            'discount'       => $row['discount'],
            'grand_total'    => $row['grand_total'],
            'payment_method' => $row['payment_method'],
            'return_status'  => $row['return_status']
        ];
    }
} catch (PDOException $e) {

    $output['data'] = [];
}

echo json_encode($output);
