<?php
session_start();
// cek apakah yang mengakses halaman ini sudah login
if ($_SESSION['role'] == "") {
    header("location:notfound.php");
}
include '../views/pageheader.php';
?>
<div class="br-pagebody">
    <div class="br-section-wrapper">

        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">
            Laporan Profit per Invoice
        </h6>
        <!-- OPTIONAL FILTER -->
        <div class="row mg-b-20">
            <div class="col-md-3">
                <input type="date" id="start" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" id="end" class="form-control">
            </div>
            <div class="col-md-2">
                <button onclick="reloadProfit()" class="btn btn-info">Filter</button>
            </div>
        </div>

        <div class="table-wrapper">
            <table id="datatable_profit" class="table display responsive nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Store</th>
                        <th>Kasir</th>
                        <th>Revenue</th>
                        <th>HPP</th>
                        <th>Profit</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>