<?php
session_start();
// cek apakah yang mengakses halaman ini sudah login
if ($_SESSION['role'] == "") {
    header("location:notfound.php");
}
include '../views/pageheader.php';
?>
<div class="br-pagebody">
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" id="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" id="end_date" class="form-control">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-primary" onclick="filterProfitProduct()">Filter</button>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary" onclick="resetFilter()">Reset</button>
        </div>
    </div>
    <div class="br-section-wrapper">

        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">
            Profit per Produk
        </h6>
        <div class="table-wrapper">
            <table id="datatable_profit_product" class="table display responsive nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                        <th>COGS</th>
                        <th>Profit</th>
                        <th>KMargin %</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>