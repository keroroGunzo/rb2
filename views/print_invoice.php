<?php
session_start();
require '../config/init.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invoice tidak valid.");
}

$id = (int)$_GET['id'];

$sqlHeader = "
    SELECT inv.*, jo.jo_number, jo.description AS jo_description,
           cust.nama_cust, cust.alamat_cust, cust.phone, cust.email
    FROM invoices inv
    LEFT JOIN job_orders jo ON jo.id = inv.job_order_id
    LEFT JOIN customers cust ON cust.id = jo.customer_id
    WHERE inv.id = :id
";
$header = $db->single($sqlHeader, [':id' => $id]);
if (!$header) die("Invoice tidak ditemukan.");

$sqlDetail = "
    SELECT it.nama_item,
           ii.deskripsi,
           ii.qty,
           COALESCE(ii.unit_price, 0) AS unit_price,
           COALESCE(ii.tax_rate, 0) AS tax_rate,
           COALESCE(ii.tax_amount, 0) AS tax_amount,
           COALESCE(ii.total, 0) AS total,
           COALESCE(ii.grand_total, (COALESCE(ii.total,0) + COALESCE(ii.tax_amount,0))) AS grand_total
    FROM invoice_items ii
    LEFT JOIN job_order_items joi ON joi.id = ii.job_order_item_id
    LEFT JOIN items it ON it.id = joi.item_id
    WHERE ii.invoice_id = :id
";
$details = $db->query($sqlDetail, [':id' => $id]);

$subTotal   = array_sum(array_column($details, 'grand_total'));
$taxRate    = (float)$header['tax_rate'];
$taxAmount  = (float)$header['tax_amount'];
$grandTotal = (float)$header['total_amount'];
$deposit = (float)$header['deposit'];
$status     = strtolower(trim($header['status'] ?? 'open'));
$pphRate   = (float)($header['pph_rate'] ?? 0);
$pphAmount = (float)($header['pph_amount'] ?? 0);
// ✅ Tambahan untuk total bayar & sisa tagihan
$sqlPay = "SELECT COALESCE(SUM(amount), 0) AS total_paid 
           FROM payments_customer 
           WHERE invoice_id = :invoice_id";
$payRow = $db->single($sqlPay, [':invoice_id' => $id]);
$totalPaid = (float)$payRow['total_paid'];
$outstanding = max(0, $grandTotal - $totalPaid);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Invoice <?= htmlspecialchars($header['invoice_number']) ?></title>
    <link href="../lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bracket.css">
    <style>
        body {
            background: #fff;
            font-size: 16px;
            /* ⬆️ dari 14px jadi 16px */
            color: #333;
            position: relative;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            font-size: 15px;
            /* sedikit lebih besar */
            padding: 6px 8px;
        }

        .tx-right {
            text-align: right;
        }

        .tx-center {
            text-align: center;
        }

        .header-company {
            text-align: right;
            font-size: 15px;
        }

        h1,
        h4,
        h6 {
            color: #111;
        }

        .notes {
            font-size: 15px;
            color: #000;
        }

        .tx-bold {
            font-weight: bold;
        }

        @media print {
            body {
                font-size: 16px !important;
                /* ⬆️ sedikit lebih besar saat print */
            }

            .table th,
            .table td {
                font-size: 16px;
            }

            .table th {
                background-color: #338cffff !important;
            }

            .invoice-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 20px 40px;
                border-bottom: 1px solid #ccc;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-body {
                margin-top: 100px;
                /* sesuaikan tinggi header di atas */

            }

            .card-body {
                padding-top: 0 !important;
            }
        }
    </style>

</head>

<body>
    <?php if ($status === 'paid'): ?>
        <div class="watermark">PAID</div>
    <?php endif; ?>

    <div class="br-pagebody" style="color: black;">
        <div class="card bd-0 shadow-base" style="margin-bottom: 5px;">
            <div class="card-body pd-30 pd-md-60">

                <div class="d-md-flex justify-content-between flex-row-reverse">
                    <h1 class="mg-b-0 tx-uppercase tx-gray-400 tx-mont tx-bold">Invoice</h1>
                    <div class="mg-t-25 mg-md-t-0 header-company text-left">
                        <h6 class="tx-primary"><img src="../img/tobaqonLogo.png" width="300" height="90"></h6>
                        <p class="lh-7">Taman Puspa Anggaswangi F2/14, Sukodono, Sidoarjo (Head Office)<br>
                            Telp: 081214821000, 08814310001<br>
                            Email: info@tobaqontrans.com</p>
                    </div>
                </div>

                <div class="row mg-t-20">
                    <div class="col-md">
                        <label class="tx-uppercase tx-13 tx-bold mg-b-20">Billed To</label>
                        <h6 class="tx-inverse"><?= htmlspecialchars($header['nama_cust'] ?? '-') ?></h6>
                        <p class="lh-7"><?= nl2br(htmlspecialchars($header['alamat_cust'] ?? '-')) ?><br>
                            Tel: <?= htmlspecialchars($header['phone'] ?? '-') ?><br>
                            Email: <?= htmlspecialchars($header['email'] ?? '-') ?></p>
                    </div>
                    <div class="col-md">
                        <label class="tx-uppercase tx-13 tx-bold mg-b-20">Invoice Information</label>
                        <p class="d-flex justify-content-between mg-b-5">
                            <span>Invoice No</span>
                            <span><?= htmlspecialchars($header['invoice_number']) ?></span>
                        </p>
                        <p class="d-flex justify-content-between mg-b-5">
                            <span>Reference</span>
                            <span><?= htmlspecialchars($header['reference'] ?? '-') ?></span>
                        </p>
                        <p class="d-flex justify-content-between mg-b-5">
                            <span>Job Order</span>
                            <span><?= htmlspecialchars($header['jo_number'] ?? '-') ?></span>
                        </p>
                        <p class="d-flex justify-content-between mg-b-5">
                            <span>Issue Date</span>
                            <span><?= !empty($header['invoice_date']) ? date('d F Y', strtotime($header['invoice_date'])) : '-' ?></span>
                        </p>
                        <p class="d-flex justify-content-between mg-b-5">
                            <span>Due Date</span>
                            <span><?= !empty($header['due_date']) ? date('d F Y', strtotime($header['due_date'])) : '-' ?></span>
                        </p>
                    </div>
                </div>


                <!-- <div class="mg-t-10">
                    <label class="tx-uppercase tx-13 tx-bold mg-b-10">Description</label>
                    <p class="notes"><?= nl2br(htmlspecialchars($header['jo_description'] ?? $header['description'] ?? '-')) ?></p>
                </div> -->

                <div class="table-responsive mg-t-20">
                    <table class="table" style="border: 1px solid #6b6b6bff;">
                        <thead class="bg-th">
                            <tr>
                                <th style="width: 30px;">No</th>
                                <th style="width: 200px;">Item</th>
                                <th style="width: 400px;">Description</th>
                                <th class="tx-center">Qty</th>
                                <th class="tx-right">Unit Price</th>
                                <th class="tx-right">Tax</th>
                                <th style="width: 130px;" class="tx-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($details as $row): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= htmlspecialchars($row['nama_item']) ?></td>
                                    <!-- <td><?= htmlspecialchars($row['deskripsi']) ?></td> -->
                                    <td>
                                        <?= nl2br(htmlspecialchars(str_replace(';', "\n", $row['deskripsi']))) ?>
                                    </td>
                                    <td class="tx-center"><?= number_format($row['qty'], 2, ',', '.') ?></td>
                                    <td class="tx-right"><?= number_format($row['unit_price'], 0, ',', '.') ?></td>
                                    <td class="tx-right"><?= number_format($row['tax_amount'], 0, ',', '.') ?></td>
                                    <td class="tx-right"><?= number_format($row['grand_total'], 0, ',', '.') ?></td>
                                </tr>
                            <?php
                                $no++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table2" style="width: 100%;border:1px solid #6b6b6bff;">
                        <tr style="padding:10px">
                            <td style="padding: 10px;vertical-align:bottom">Please pay the invoice in <b>FULL AMOUNT</b> ( Without Bank charge )</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="width:100px" class="tx-right tx-bold">Sub Total</td>
                            <td style="width: 150px;padding:10px 10px 10px 0px" class="tx-right"><?= number_format($subTotal, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="width:100px" class="tx-right tx-bold">Tax (<?= htmlspecialchars($taxRate) ?>%)</td>
                            <td style="width: 150px; padding:10px 10px 10px 0px" class="tx-right"><?= number_format($taxAmount, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;padding-left:10px">Bank Account:</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="tx-right tx-bold">PPH (<?= htmlspecialchars($pphRate) ?>%)</td>
                            <td class="tx-right" style="padding:10px 10px 10px 0px"><?= number_format($pphAmount, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;padding-left:10px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="tx-right tx-bold">Deposit</td>
                            <td class="tx-right" style="padding:10px 10px 10px 0px"><?= number_format($deposit, 0, ',', '.') ?></td>
                        </tr>      
                        <tr>
                            <td style="vertical-align:top;padding-left : 10px">Name : PT. Tobaqon Antobaq Trans<br>AC No. : 141 - 0001 - 8077 - 00<br>Bank : MANDIRI Cab Sidoarjo</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="width: 150px; padding:10px 10px 10px 0px" class="tx-right tx-bold">Total</td>
                            <td style="width: 150px; padding:10px 10px 10px 0px" class="tx-right">
                                <h4 class="tx-teal tx-bold"><?= number_format($grandTotal, 0, ',', '.') ?></h4>
                                <!-- <h4 class="tx-teal tx-bold"><?= number_format($Total, 0, ',', '.') ?></h4> -->
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php
        // Fungsi baru: terbilang yang aman untuk PHP 8+
        // - terbilang_int(int $n) : rekursif hanya menerima integer
        // - terbilang(float|int $angka) : wrapper yang menerima float, memisah integer & desimal (sen)
        function terbilang_int(int $angka): string
        {
            $angka = abs($angka);
            $satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

            if ($angka < 12) {
                return " " . $satuan[$angka];
            }
            if ($angka < 20) {
                return terbilang_int($angka - 10) . " Belas";
            }
            if ($angka < 100) {
                $puluh = intdiv($angka, 10);
                $sisa = $angka % 10;
                return terbilang_int($puluh) . " Puluh" . ($sisa ? terbilang_int($sisa) : "");
            }
            if ($angka < 200) {
                return " Seratus" . terbilang_int($angka - 100);
            }
            if ($angka < 1000) {
                $ratus = intdiv($angka, 100);
                $sisa = $angka % 100;
                return terbilang_int($ratus) . " Ratus" . ($sisa ? terbilang_int($sisa) : "");
            }
            if ($angka < 2000) {
                return " Seribu" . terbilang_int($angka - 1000);
            }
            if ($angka < 1000000) {
                $ribuan = intdiv($angka, 1000);
                $sisa = $angka % 1000;
                return terbilang_int($ribuan) . " Ribu" . ($sisa ? terbilang_int($sisa) : "");
            }
            if ($angka < 1000000000) {
                $juta = intdiv($angka, 1000000);
                $sisa = $angka % 1000000;
                return terbilang_int($juta) . " Juta" . ($sisa ? terbilang_int($sisa) : "");
            }
            if ($angka < 1000000000000) {
                $milyar = intdiv($angka, 1000000000);
                $sisa = $angka % 1000000000;
                return terbilang_int($milyar) . " Milyar" . ($sisa ? terbilang_int($sisa) : "");
            }
            // untuk angka lebih besar
            $triliun = intdiv($angka, 1000000000000);
            $sisa = $angka % 1000000000000;
            return terbilang_int($triliun) . " Triliun" . ($sisa ? terbilang_int($sisa) : "");
        }

        function terbilang($angka): string
        {
            // pastikan angka adalah numeric
            if (!is_numeric($angka)) return "";

            // ubah jadi float, bulatkan 2 desimal
            $angka = (float)$angka;
            $rounded = round($angka, 2);

            // ambil bagian integer dan desimal (sen)
            $integerPart = (int) floor($rounded);
            $decimalPart = (int) round(($rounded - $integerPart) * 100); // 0..99

            $hasil = trim(terbilang_int($integerPart));
            if ($hasil === "") $hasil = "Nol";

            if ($decimalPart > 0) {
                // tambahkan bagian desimal sebagai "koma xx sen" atau "koma xx"
                $hasil .= " KOMA " . trim(terbilang_int($decimalPart));
            }

            return $hasil;
        }

        // Pemakaian: gunakan $grandTotal (float) lalu tampilkan
        $grandTotalNumeric = (float)($grandTotal ?? 0.0);

        // contoh: bulatkan ke 2 desimal untuk tampil
        $terbilangText = terbilang($grandTotalNumeric) . " RUPIAH";
        $terbilangUpper = mb_strtoupper($terbilangText, 'UTF-8');
        ?>
        <div style="margin-top: 15px;margin-left:60px; width: 95%;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 70%; vertical-align: top;">
                        <b>Terbilang:</b><br>
                        <i><?= htmlspecialchars($terbilangUpper) ?></i>
                    </td>
                    <td style="width: 30%; text-align: center;">
                        <b>PT. TOBAQON ANTOBAQ TRANS</b><br><br><br><br><br>
                        <u>(M. THORIQ HIDAYAT)</u><br>
                        <span style="font-size: 13px;">DIREKTUR UTAMA</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php
    $status = strtolower(trim($header['status'] ?? ''));
    if ($status === 'paid') {
        $wmText = 'PAID';
        $wmColor = 'rgba(0,128,0,0.2)';
    } elseif ($status === 'open') {
        $wmText = 'UNPAID';
        $wmColor = 'rgba(255,0,0,0.15)';
    } elseif ($status === 'partial') {
        $wmText = 'PARTIAL';
        $wmColor = 'rgba(255, 196, 0, 0.2)';
    }
    ?>
    <?php if (!empty($wmText)): ?>
        <style>
            .watermark {
                position: fixed !important;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-30deg) !important;
                font-size: 180px;
                font-weight: 900;
                text-transform: uppercase;
                color: <?= $wmColor ?>;
                opacity: 1;
                z-index: 9999;
                pointer-events: none;
                user-select: none;
                text-shadow: 0 0 2px rgba(0, 0, 0, 0.05);
            }

            .br-pagebody,
            .card {
                position: relative;
                z-index: 10;
            }

            @media print {
                .watermark {
                    position: fixed !important;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) rotate(-30deg) !important;
                    color: <?= $wmColor ?> !important;
                    opacity: 1 !important;
                    z-index: 9999 !important;
                    mix-blend-mode: multiply !important;

                }

                .table {
                    border: 1px solid #e4e7e9ff;
                }

                .table2 {
                    border: 1px solid #e4e7e9ff;
                }

                body {
                    font-size: 12px;
                }
            }
        </style>
        <div class="watermark"><?= strtoupper($wmText) ?></div>
    <?php endif; ?>

    <script>
        window.addEventListener("load", function() {
            console.log("Watermark status:", "<?= strtoupper($status) ?>");
            setTimeout(() => window.print(), 400);
        });
    </script>
</body>

</html>