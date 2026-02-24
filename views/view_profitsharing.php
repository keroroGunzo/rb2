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
        <div class="table-wrapper">
            <!-- <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right" onclick="setStatusInsert()">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button> -->
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>JO NUMBER</th>
                        <th>JO STATUS</th>
                        <th>JO TOTAL</th>
                        <th>FINAL INVOICE</th>
                        <!-- <th>OUTSTANDING</th> -->
                        <th>PROFIT TYPE</th>
                        <th>TONAGE</th>
                        <th>INVESTOR SHARE THORIQ</th>
                        <th>INVESTOR SHARE IMRON</th>
                        <th>MARKETING SHARE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>

<!-- Modal Profit Sharing Detail -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Profit Sharing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="tblProfitDetail">
                    <thead class="thead-colored thead-indigo">
                        <tr>
                            <th>No</th>
                            <th>Invoice Number</th>
                            <th>Payment Date</th>
                            <th>Payment Amount</th>
                            <th>Profit Type</th>
                            <th>Tonase (Ton)</th> <!-- ✅ kolom baru -->
                            <th>Thoriq</th>
                            <th>Imron</th>
                            <th>Marketing</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- isi via ajax -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>