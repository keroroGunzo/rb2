<?php
function closeJobOrderIfPaid($db, $job_order_id)
{
    // === 1️⃣ Ambil total Job Order (tanpa pajak header) ===
    $rowJO = $db->single("
        SELECT COALESCE(total_amount, 0) AS jo_total
        FROM job_orders
        WHERE id = :id
    ", [':id' => $job_order_id]);

    $joTotal = (float)($rowJO['jo_total'] ?? 0);

    // === 2️⃣ Cek apakah sudah ada invoice ===
    $invoiceRow = $db->single("
        SELECT COUNT(*) AS cnt
        FROM invoices
        WHERE job_order_id = :id
    ", [':id' => $job_order_id]);

    $hasInvoice = ($invoiceRow['cnt'] > 0);

    // === 3️⃣ Ambil total pembayaran customer + total PPh ===
    $rowPay = $db->single("
        SELECT 
            COALESCE(SUM(pc.amount), 0) AS paid_total,
            COALESCE(SUM(inv.pph_amount), 0) AS total_pph
        FROM invoices inv
        LEFT JOIN payments_customer pc ON pc.invoice_id = inv.id
        WHERE inv.job_order_id = :id
    ", [':id' => $job_order_id]);

    $paidTotal = (float)($rowPay['paid_total'] ?? 0);
    $totalPph  = (float)($rowPay['total_pph'] ?? 0);

    // === 4️⃣ Hitung total efektif ===
    $effectivePaid = $paidTotal + $totalPph;

    // === 5️⃣ Tentukan status baru ===
    if (!$hasInvoice) {
        // Belum ada invoice
        $newStatus = "Open";
    } else {
        // Sudah ada invoice pasti minimal "In Progress"
        if ($effectivePaid >= $joTotal && $joTotal > 0) {
            $newStatus = "Closed"; // Lunas
        } else {
            $newStatus = "In Progress"; // Invoice ada, tapi belum lunas
        }
    }

    // === 6️⃣ Update status JO ===
    $db->execute("
        UPDATE job_orders
        SET status = :status, updated_at = NOW()
        WHERE id = :id
    ", [
        ':status' => $newStatus,
        ':id'     => $job_order_id
    ]);

    // Debug return
    return [
        'job_order_id'   => $job_order_id,
        'total_jo'       => $joTotal,
        'paid'           => $paidTotal,
        'pph'            => $totalPph,
        'effective_paid' => $effectivePaid,
        'has_invoice'    => $hasInvoice,
        'status'         => $newStatus
    ];
}



function calculateProfitShares($db, $job_order_id, $invoice_total)
{
    $shares = [
        'thoriq'         => ['amount' => 0, 'percent' => 0],
        'imron'          => ['amount' => 0, 'percent' => 0],
        'marketing'      => ['amount' => 0, 'percent' => 0],
        'marketing_name' => null,
        'profit_type'    => 'unknown'
    ];

    // Ambil data JO + investor
    $jo = $db->single("
        SELECT jo.profit_type, jo.tonase, jo.rate,
               u.fullname AS investor_name, u.role AS investor_role
        FROM job_orders jo
        JOIN users u ON u.id = jo.investor_id
        WHERE jo.id = :id
    ", [':id' => $job_order_id]);

    if (!$jo) return $shares;

    $profitType    = strtolower(trim($jo['profit_type'] ?? ''));
    $tonase        = (float)($jo['tonase'] ?? 0);
    $rate          = (float)($jo['rate'] ?? 0);
    $investorName  = strtolower(trim($jo['investor_name'] ?? ''));
    $investorRole  = strtolower(trim($jo['investor_role'] ?? ''));

    $shares['profit_type'] = $profitType;

    // === 1️⃣ PROFIT TYPE: TONASE ===
    if ($profitType === 'tonase') {
        // ambil nama dari DB
        $thoriq = $db->single("SELECT fullname FROM users WHERE LOWER(fullname) LIKE '%thoriq%' LIMIT 1");
        $imron  = $db->single("SELECT fullname FROM users WHERE LOWER(fullname) LIKE '%imron%' LIMIT 1");

        $thoriqName = $thoriq['fullname'] ?? 'Thoriq';
        $imronName  = $imron['fullname'] ?? 'Imron';

        // ✅ perhitungan tonase baru:
        // Thoriq = tonase * 200
        // Imron  = (tonase * 3000) - (tonase * 200)
        $thoriqAmount = $tonase * 200;
        $imronAmount  = ($tonase * 3000) - $thoriqAmount;
        $total = $thoriqAmount + $imronAmount;

        $shares['thoriq'] = [
            'amount'  => $thoriqAmount,
            'percent' => $total > 0 ? round(($thoriqAmount / $total) * 100, 2) : 0
        ];
        $shares['imron'] = [
            'amount'  => $imronAmount,
            'percent' => $total > 0 ? round(($imronAmount / $total) * 100, 2) : 0
        ];
        $shares['marketing'] = ['amount' => 0, 'percent' => 0];
        return $shares;
    }

    // === 2️⃣ PROFIT TYPE: INVESTOR ===
    if ($investorRole === 'owner') {
        if (strpos($investorName, 'thoriq') !== false) {
            $imron = $db->single("SELECT fullname FROM users WHERE LOWER(fullname) LIKE '%imron%' LIMIT 1");
            $shares['thoriq'] = [
                'amount'  => $invoice_total * 0.7,
                'percent' => 70
            ];
            $shares['imron'] = [
                'amount'  => $invoice_total * 0.3,
                'percent' => 30
            ];
        } elseif (strpos($investorName, 'imron') !== false) {
            $thoriq = $db->single("SELECT fullname FROM users WHERE LOWER(fullname) LIKE '%thoriq%' LIMIT 1");
            $shares['imron'] = [
                'amount'  => $invoice_total * 0.7,
                'percent' => 70
            ];
            $shares['thoriq'] = [
                'amount'  => $invoice_total * 0.3,
                'percent' => 30
            ];
        }
    }

    // === 3️⃣ PROFIT TYPE: MARKETING ===
    elseif ($investorRole === 'marketing') {
        $thoriq = $db->single("SELECT fullname FROM users WHERE LOWER(fullname) LIKE '%thoriq%' LIMIT 1");
        $shares['thoriq'] = [
            'amount'  => $invoice_total * 0.5,
            'percent' => 50
        ];
        $shares['marketing'] = [
            'amount'  => $invoice_total * 0.5,
            'percent' => 50
        ];
        $shares['marketing_name'] = $jo['investor_name'];
    }

    return $shares;
}
