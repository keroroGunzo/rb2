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

        <h5>Laporan Laba Rugi</h5>

        <!-- FILTER -->
        <div class="row mb-4">
            <div class="col-md-3">
                <input type="date" id="lr_start" class="form-control">
            </div>
            <div class="col-md-3">
                <input type="date" id="lr_end" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-block" onclick="loadLabaRugi()">
                    🔍 Filter
                </button>
            </div>
            <div class="col-md-4" style="vertical-align: center;">
                <small class="text-muted">
                    <h6>Menampilkan  data dari Periode :&nbsp; <b id="range_label"></b></h6>
                </small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-1">
                    <div class="card-body">
                        <small class="text-muted">Net Revenue</small>
                        <h3 id="lr_net_revenue" class="mb-0 font-weight-bold text-primary">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-1">
                    <div class="card-body">
                        <small class="text-muted">Net COGS</small>
                        <h3 id="lr_net_cogs" class="mb-0 font-weight-bold text-dark">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-1">
                    <div class="card-body">
                        <small class="text-muted">Laba Kotor</small>
                        <h3 id="lr_profit" class="mb-0 font-weight-bold">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-1">
                    <div class="card-body">
                        <small class="text-muted">Gross Margin</small>
                        <h3 id="lr_margin" class="mb-0 font-weight-bold">0%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- REPORT -->
        <div class="card shadow-sm border-1">
            <div class="card-body">

                <h6 class="mb-3">Detail Perhitungan</h6>

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Penjualan</span>
                    <span id="lr_revenue"></span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom text-danger">
                    <span>Diskon</span>
                    <span id="lr_discount"></span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom text-warning">
                    <span>Retur</span>
                    <span id="lr_refund"></span>
                </div>

                <div class="d-flex justify-content-between py-2 border-top font-weight-bold">
                    <span>Net Revenue</span>
                    <span id="lr_net_revenue_detail"></span>
                </div>

                <hr style="border: solid 2px grey;">

                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>COGS</span>
                    <span id="lr_cogs"></span>
                </div>

                <div class="d-flex justify-content-between py-2 border-bottom text-info">
                    <span>Reverse Return</span>
                    <span id="lr_cogs_reverse"></span>
                </div>

                <div class="d-flex justify-content-between py-2 font-weight-bold border-bottom">
                    <span>Net COGS</span>
                    <span id="lr_net_cogs_detail"></span>
                </div>
                <div class="d-flex justify-content-between py-2 font-weight-bold border-bottom">
                    <span>Biaya Operasional</span>
                    <span id="lr_expense"></span>
                </div>
                <div class="d-flex justify-content-between py-2 font-weight-bold">
                    <span>Net Profit</span>
                    <span id="lr_net_profit"></span>
                </div>

            </div>
        </div>

    </div>
</div>