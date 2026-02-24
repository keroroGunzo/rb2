<?php
// function getProfitSharing($db, $job_order_id, $payment_amount)
// {
//     // Ambil data job order + investor
//     $row = $db->single("
//         SELECT 
//             jo.id,
//             LOWER(jo.profit_type) AS profit_type,
//             jo.tonase,
//             jo.rate,
//             jo.investor_id,
//             u.fullname AS investor_name,
//             u.role AS investor_role
//         FROM job_orders jo
//         LEFT JOIN users u ON u.id = jo.investor_id
//         WHERE jo.id = :id
//     ", [':id' => $job_order_id]);

//     if (!$row) return [];

//     $profitType   = $row['profit_type'];
//     $tonase       = (float)($row['tonase'] ?? 0);
//     $rate         = (float)($row['rate'] ?? 0);
//     $investorName = strtolower($row['investor_name'] ?? '');
//     $investorRole = strtolower($row['investor_role'] ?? '');
//     $result       = [];

//     /* =====================================================
//      * 1️⃣ PROFIT TYPE: TONASE
//      * 100% THORIQ (tonase × rate)
//      * ===================================================== */
//     if ($profitType === 'tonase') {
//         $totalTonase = $tonase * $rate;

//         $thoriq = $db->single("
//             SELECT fullname 
//             FROM users 
//             WHERE LOWER(fullname) LIKE '%thoriq%' 
//             LIMIT 1
//         ");

//         $result[] = [
//             'name'    => $thoriq['fullname'] ?? 'Thoriq',
//             'share'   => $totalTonase,
//             'percent' => 100
//         ];

//         return $result;
//     }

//     /* =====================================================
//      * 2️⃣ PROFIT TYPE: INVESTOR (LOGIC LAMA - JANGAN DIUBAH)
//      * ===================================================== */
//     if ($investorRole === 'owner') {

//         if (strpos($investorName, 'thoriq') !== false) {
//             $imron = $db->single("
//                 SELECT fullname 
//                 FROM users 
//                 WHERE LOWER(fullname) LIKE '%imron%' 
//                 LIMIT 1
//             ");

//             $result[] = [
//                 'name'    => ucfirst($investorName),
//                 'share'   => $payment_amount * 0.70,
//                 'percent' => 70
//             ];
//             $result[] = [
//                 'name'    => $imron['fullname'] ?? 'Imron',
//                 'share'   => $payment_amount * 0.30,
//                 'percent' => 30
//             ];

//             return $result;
//         }

//         elseif (strpos($investorName, 'imron') !== false) {
//             $thoriq = $db->single("
//                 SELECT fullname 
//                 FROM users 
//                 WHERE LOWER(fullname) LIKE '%thoriq%' 
//                 LIMIT 1
//             ");

//             $result[] = [
//                 'name'    => ucfirst($investorName),
//                 'share'   => $payment_amount * 0.70,
//                 'percent' => 70
//             ];
//             $result[] = [
//                 'name'    => $thoriq['fullname'] ?? 'Thoriq',
//                 'share'   => $payment_amount * 0.30,
//                 'percent' => 30
//             ];

//             return $result;
//         }
//     }

//     /* =====================================================
//      * 3️⃣ PROFIT TYPE: MARKETING (ROLE USERS)
//      * ===================================================== */
//     if ($investorRole === 'marketing') {
//         $thoriq = $db->single("
//             SELECT fullname 
//             FROM users 
//             WHERE LOWER(fullname) LIKE '%thoriq%' 
//             LIMIT 1
//         ");

//         $result[] = [
//             'name'    => $thoriq['fullname'] ?? 'Thoriq',
//             'share'   => $payment_amount * 0.50,
//             'percent' => 50
//         ];
//         $result[] = [
//             'name'    => ucfirst($row['investor_name']),
//             'share'   => $payment_amount * 0.50,
//             'percent' => 50
//         ];

//         return $result;
//     }

//     /* =====================================================
//      * 4️⃣ NON TONASE (BARU)
//      * TANPA INVESTOR / TANPA MARKETING
//      * 100% THORIQ
//      * ===================================================== */
//     if ($profitType === 'non_tonase') {
//         $thoriq = $db->single("
//             SELECT fullname 
//             FROM users 
//             WHERE LOWER(fullname) LIKE '%thoriq%' 
//             LIMIT 1
//         ");

//         return [[
//             'name'    => $thoriq['fullname'] ?? 'Thoriq',
//             'share'   => $payment_amount,
//             'percent' => 100
//         ]];
//     }

//     /* =====================================================
//      * 5️⃣ FALLBACK AMAN
//      * ===================================================== */
//     $thoriq = $db->single("
//         SELECT fullname 
//         FROM users 
//         WHERE LOWER(fullname) LIKE '%thoriq%' 
//         LIMIT 1
//     ");

//     return [[
//         'name'    => $thoriq['fullname'] ?? 'Thoriq',
//         'share'   => $payment_amount,
//         'percent' => 100
//     ]];
// }


/**
 * ===============================
 * PROFIT SHARING UTAMA (SUDAH ADA)
 * ===============================
 */
function getProfitSharing($db, $job_order_id, $payment_amount)
{
    $row = $db->single("
        SELECT 
            jo.id,
            jo.profit_type,
            jo.tonase,
            jo.rate,
            jo.investor_id,
            u.fullname AS investor_name,
            u.role AS investor_role
        FROM job_orders jo
        LEFT JOIN users u ON u.id = jo.investor_id
        WHERE jo.id = :id
    ", [':id' => $job_order_id]);

    if (!$row) return [];

    $profitType   = strtolower(trim($row['profit_type'] ?? 'investor'));
    $tonase       = (float)($row['tonase'] ?? 0);
    $rate         = (float)($row['rate'] ?? 0);
    $investorName = strtolower($row['investor_name'] ?? '');
    $investorRole = strtolower($row['investor_role'] ?? '');
    $result       = [];

    /* ======================
       TONASE → 100% THORIQ
    ====================== */
    if ($profitType === 'tonase') {

        $totalTonase = $tonase * $rate;

        $thoriq = $db->single("
            SELECT fullname 
            FROM users 
            WHERE LOWER(fullname) LIKE '%thoriq%' 
            LIMIT 1
        ");

        $result[] = [
            'name'    => $thoriq['fullname'] ?? 'Thoriq',
            'share'   => $totalTonase,
            'percent' => 100
        ];

        return $result;
    }

    /* ======================
       INVESTOR (70 : 30)
    ====================== */
    if ($investorRole === 'owner') {

        if (strpos($investorName, 'thoriq') !== false) {

            $imron = $db->single("
                SELECT fullname 
                FROM users 
                WHERE LOWER(fullname) LIKE '%imron%' 
                LIMIT 1
            ");

            $result[] = [
                'name'    => 'Thoriq',
                'share'   => $payment_amount * 0.70,
                'percent' => 70
            ];

            $result[] = [
                'name'    => $imron['fullname'] ?? 'Imron',
                'share'   => $payment_amount * 0.30,
                'percent' => 30
            ];

        } elseif (strpos($investorName, 'imron') !== false) {

            $thoriq = $db->single("
                SELECT fullname 
                FROM users 
                WHERE LOWER(fullname) LIKE '%thoriq%' 
                LIMIT 1
            ");

            $result[] = [
                'name'    => 'Imron',
                'share'   => $payment_amount * 0.70,
                'percent' => 70
            ];

            $result[] = [
                'name'    => $thoriq['fullname'] ?? 'Thoriq',
                'share'   => $payment_amount * 0.30,
                'percent' => 30
            ];
        }
    }

    /* ======================
       MARKETING (50 : 50)
    ====================== */
    elseif ($investorRole === 'marketing') {

        $thoriq = $db->single("
            SELECT fullname 
            FROM users 
            WHERE LOWER(fullname) LIKE '%thoriq%' 
            LIMIT 1
        ");

        $result[] = [
            'name'    => $thoriq['fullname'] ?? 'Thoriq',
            'share'   => $payment_amount * 0.50,
            'percent' => 50
        ];

        $result[] = [
            'name'    => ucfirst($investorName),
            'share'   => $payment_amount * 0.50,
            'percent' => 50
        ];
    }

    /* ======================
       FALLBACK → 100% THORIQ
    ====================== */
    if (empty($result)) {

        $thoriq = $db->single("
            SELECT fullname 
            FROM users 
            WHERE LOWER(fullname) LIKE '%thoriq%' 
            LIMIT 1
        ");

        $result[] = [
            'name'    => $thoriq['fullname'] ?? 'Thoriq',
            'share'   => $payment_amount,
            'percent' => 100
        ];
    }

    return $result;
}


/**
 * =====================================================
 * 🔥 ALIAS FUNCTION (INI YANG HILANG & BIKIN ERROR)
 * =====================================================
 * Digunakan oleh REPORT (mdl_report_labarugi_per_jo.php)
 */
function calculateProfitShares($db, $job_order_id, $amount)
{
    $raw = getProfitSharing($db, $job_order_id, $amount);

    $result = [
        'thoriq'    => ['amount' => 0, 'percent' => 0],
        'imron'     => ['amount' => 0, 'percent' => 0],
        'marketing' => ['amount' => 0, 'percent' => 0],
    ];

    foreach ($raw as $r) {
        $name = strtolower($r['name']);

        if (strpos($name, 'thoriq') !== false) {
            $result['thoriq'] = [
                'amount'  => (float)$r['share'],
                'percent' => (float)$r['percent']
            ];
        } elseif (strpos($name, 'imron') !== false) {
            $result['imron'] = [
                'amount'  => (float)$r['share'],
                'percent' => (float)$r['percent']
            ];
        } elseif (strpos($name, 'marketing') !== false) {
            $result['marketing'] = [
                'amount'  => (float)$r['share'],
                'percent' => (float)$r['percent']
            ];
        }
    }

    return $result;
}

