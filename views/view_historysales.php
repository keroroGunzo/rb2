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
        <div class="table-wrapper">
            <br /> <br />
            <table id="datatable1" class="table display responsive data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>INVOICE</th>
                        <th>CREATED AT</th>
                        <th>NAMA TOKO</th>
                        <th>KASIR</th>
                        <th>TOTAL</th>
                        <th>DISKON</th>
                        <th>GRAND TOTAL</th>
                        <th>PEMBAYARAN</th>
                        <th>STATUS RETUR</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>
<!-- BASIC MODAL -->
<div id="modalSaleReturn" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Retur Penjualan</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    onclick="">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="sale_id">

                <table class="table table-bordered" id="tblSaleReturn">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Terjual</th>
                            <th>Sudah Retur</th>
                            <th>Sisa</th>
                            <th>Qty Retur</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button onclick="saveSaleReturn()" class="btn btn-primary">Simpan</button>
            </div>

        </div>
    </div>
</div>