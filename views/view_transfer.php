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
            <button type="button"
                class="btn btn-primary btn-sm"
                data-toggle="modal"
                data-target="#modal"
                onclick="resetTransferModal()">
                Transfer Baru
            </button>
            <br /> <br />
            <table id="datatable1" class="table display responsive data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>id</th>
                        <th>Dari</th>
                        <th>Tujuan</th>
                        <th>Total Item</th>
                        <th>Total Qty</th>
                        <th>Created at</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>

<div id="modal" class="modal fade" data-backdrop="static">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Form Transfer Barang</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal">
                    &times;
                </button>
            </div>

            <form id="frmTransfer"
                data-models="mdl_transfer"
                data-hapus="mdl_deletetransfer"
                action="javascript:;">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-3">
                            <label>Dari Tipe</label>
                            <select name="from_type"
                                class="form-control select2"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="warehouse">Warehouse</option>
                                <option value="store">Store</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Dari Lokasi</label>
                            <select name="from_id"
                                class="form-control select2"
                                required></select>
                        </div>

                        <div class="col-md-3">
                            <label>Ke Tipe</label>
                            <select name="to_type"
                                class="form-control select2"
                                required>
                                <option value="">-- Pilih --</option>
                                <option value="warehouse">Warehouse</option>
                                <option value="store">Store</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Ke Lokasi</label>
                            <select name="to_id"
                                class="form-control select2"
                                required></select>
                        </div>

                    </div>

                    <hr>

                    <table id="tblTransfer" style="width:100%;border:1px solid #e2e2e2;"
                        class="table table-bordered">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th>Produk</th>
                                <th width="120">Qty</th>
                                <th width="60">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button"
                        class="btn btn-primary btn-sm"
                        id="btnAddTransfer"
                        disabled
                        onclick="addTransferRow()">
                        + Tambah Item
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success"
                        id="simpanData"
                        onclick="insertFormdata('frmTransfer')">
                        Simpan
                    </button>
                    <button class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<div id="modalDetailTransfer" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Transfer</h5>
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered" style="width:100%;border:1px solid #e2e2e2;">
                    <thead style="background-color: #f0f0f0;">
                        <tr>
                            <th>SKU</th>
                            <th>Nama</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="detailTransferBody"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>