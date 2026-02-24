<?php
function setInvoiceStatus($db, $invoice_id)
{
    // hitung total paid
    $paidRow = $db->single("
        SELECT COALESCE(SUM(amount),0) AS paid 
        FROM payments_customer 
        WHERE invoice_id=:id
    ", [':id' => $invoice_id]);

    $paidTotal = (float)($paidRow['paid'] ?? 0);

    // ambil total invoice
    $inv = $db->single("SELECT total_amount FROM invoices WHERE id=:id", [':id' => $invoice_id]);
    if (!$inv) {
        throw new Exception("Invoice tidak ditemukan untuk update status.");
    }
    $invTotal = (float)$inv['total_amount'];

    // tentukan status
    if ($paidTotal >= $invTotal) {
        $status = 'paid';
    } elseif ($paidTotal > 0 && $paidTotal < $invTotal) {
        $status = 'partial';
    } else {
        $status = 'open';
    }

    // update
    $db->execute("UPDATE invoices SET status=:status WHERE id=:id", [
        ':status' => $status,
        ':id'     => $invoice_id
    ]);

    return $status;
}
