<?php
require '../config/init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../views/pageheader.php';
?>

<div class="br-pagebody">
    <div class="br-section-wrapper">

        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">
            Riwayat Pergerakan Stok
        </h6>

        <div class="table-wrapper">
            <table id="datatable1"
                class="table display responsive nowrap data-table"
                style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Qty</th>
                        <th>Tipe</th>
                        <th>User</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>