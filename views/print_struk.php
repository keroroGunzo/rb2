<?php
require '../config/init.php';

$id = intval($_GET['id']);

$trx = $db->single("
SELECT 
s.invoice_no,
s.created_at,
s.total,
s.discount,
s.grand_total,
s.payment_method,
st.name as store_name,
u.name as cashier
FROM sales s
JOIN stores st ON st.id = s.store_id
JOIN users u ON u.id = s.cashier_id
WHERE s.id = :id
", [
    ':id' => $id
]);

$items = $db->query("
SELECT 
p.name as product_name,
si.qty,
si.price,
si.subtotal
FROM sale_items si
JOIN products p ON p.id = si.product_id
WHERE si.sale_id = :id
", [
    ':id' => $id
]);
?>

<!DOCTYPE html>
<html>

<head>

    <style>
        body {
            font-family: monospace;
            width: 280px;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed black;
            margin: 5px 0;
        }

        table {
            width: 100%;
        }
    </style>

</head>

<body onload="window.print()">

    <div class="center">
        <b><?= $trx['store_name'] ?></b><br>
    </div>

    <div class="line"></div>

    Invoice : <?= $trx['invoice_no'] ?><br>
    Tanggal : <?= $trx['created_at'] ?><br>
    Kasir : <?= $trx['cashier'] ?><br>

    <div class="line"></div>

    <?php foreach ($items as $item) { ?>

        <?= $item['product_name'] ?><br>

        <?= $item['qty'] ?> x <?= number_format($item['price']) ?>
        <span style="float:right">
            <?= number_format($item['subtotal']) ?>
        </span>

        <br>

    <?php } ?>

    <div class="line"></div>

    <table>
        <tr>
            <td>Total</td>
            <td align="right"><?= number_format($trx['total']) ?></td>
        </tr>

        <tr>
            <td>Diskon</td>
            <td align="right"><?= number_format($trx['discount']) ?></td>
        </tr>

        <tr>
            <td><b>Grand Total</b></td>
            <td align="right"><b><?= number_format($trx['grand_total']) ?></b></td>
        </tr>
    </table>

    <div class="line"></div>

    Metode Bayar : <?= strtoupper($trx['payment_method']) ?>

    <br><br>

    <div class="center">
        Terima Kasih 🙏
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>