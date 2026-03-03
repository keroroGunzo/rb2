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
        <button type="button"
            class="btn btn-primary btn-sm"
            data-toggle="modal"
            data-target="#modal"
            onclick="">
            Adjustment Baru
        </button>

        <br><br>

        <div class="table-wrapper">
            <table id="datatable1"
                class="table display responsive nowrap data-table"
                style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id</th>
                        <th>Lokasi</th>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th>Qty</th>
                        <th>Created At</th>
                        <th>Note</th>
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Adjustment -->
<div id="modal"
    class="modal fade"
    data-backdrop="static">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Form Penyesuaian Stok</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form id="frmAdjustment"
                data-models="mdl_adjustment"
                data-hapus="mdl_deleteadjustment"
                data-getID="mdl_getidadjustment"
                class="form-horizontal form-bordered"
                action="javascript:;">

                <div class="modal-body">

                    <div class="form-group">
                        <label>Lokasi Tipe</label>
                        <select name="location_type"
                            class="form-control select2"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="warehouse">Warehouse</option>
                            <option value="store">Store</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <select name="location_id"
                            class="form-control select2"
                            required></select>
                    </div>

                    <div class="form-group">
                        <label>Produk</label>
                        <select name="product_id"
                            class="form-control select2"
                            required></select>
                    </div>

                    <div class="form-group">
                        <label>Tipe Adjustment</label>
                        <select name="adjustment_type"
                            class="form-control"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="add">Tambah Stok</option>
                            <option value="subtract">Kurangi Stok</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Qty</label>
                        <input type="number"
                            name="qty"
                            class="form-control"
                            min="1"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Alasan</label>
                        <input type="text"
                            name="note"
                            class="form-control"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success"
                        id="simpanData"
                        onclick="insertFormdata('frmAdjustment')">
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