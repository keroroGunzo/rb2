<?php
require '../config/init.php';
header('Content-Type: application/json');

$response = [];

try {

    $start = $_GET['start'] ?? null;
    $end   = $_GET['end'] ?? null;

    $where = "";
    $params = [];

    if ($start && $end) {
        $where = "WHERE DATE(s.created_at) BETWEEN :start AND :end";
        $params[':start'] = $start;
        $params[':end']   = $end;
    }

    $expense = $db->single("
        SELECT COALESCE(SUM(amount),0) as total
        FROM expenses
        WHERE DATE(expense_date) BETWEEN :start AND :end
    ", $params)['total'];

    $sql = "
    SELECT
        COALESCE(SUM(si.price * si.qty),0) AS revenue,
        COALESCE(SUM(si.cost * si.qty),0) AS cogs,
        COALESCE(SUM(s.discount),0) AS discount,

        COALESCE((
            SELECT SUM(sri.price * sri.qty)
            FROM sale_return_items sri
        ),0) AS refund,

        COALESCE((
            SELECT SUM(sri.cost * sri.qty)
            FROM sale_return_items sri
        ),0) AS cogs_reverse

    FROM sales s
    JOIN sale_items si ON si.sale_id = s.id

    $where
    ";

    $row = $db->single($sql, $params);

    $revenue = (float)$row['revenue'];
    $cogs = (float)$row['cogs'];
    $discount = (float)$row['discount'];
    $refund = (float)$row['refund'];
    $cogs_reverse = (float)$row['cogs_reverse'];

    // 🔥 CALCULATION
    $net_revenue = $revenue - $discount - $refund;
    $net_cogs = $cogs - $cogs_reverse;
    $gross_profit = $net_revenue - $net_cogs;
    $gross_margin = $net_revenue > 0 ? ($gross_profit / $net_revenue) * 100 : 0;

    $response = [
        'revenue' => $revenue,
        'discount' => $discount,
        'refund' => $refund,
        'net_revenue' => $net_revenue,
        'cogs' => $cogs,
        'cogs_reverse' => $cogs_reverse,
        'net_cogs' => $net_cogs,
        'gross_profit' => $gross_profit,
        'gross_margin' => $gross_margin,
        'expense' => $expense,
        'net_profit' => $gross_profit - $expense
        
    ];
} catch (Exception $e) {
    $response = ['error' => $e->getMessage()];
}
echo json_encode($response);
