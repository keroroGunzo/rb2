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
                        <th>SKU</th>
                        <th>NAMA PRODUK</th>
                        <th>TIPE LOKASI</th>
                        <th>NAMA LOKASI</th>
                        <th>STATUS PRODUK</th>
                        <th>QTY</th>
                        <th>TANGGAL UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>
<!-- BASIC MODAL -->
<div id="modal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Form Barang Masuk</h5>
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        onclick="resetBarangMasukModal()">
                    <span>&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm" data-models="mdl_barangmasuk" data-hapus="mdl_deletebarangmasuk" data-getID="mdl_getidbarangmasuk" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body">

                    <div class="row mb-3">
                        <input type="hidden" name="id" data-json="id">
                        <!-- Supplier -->
                        <div class="col-md-4">
                            <label>Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                    class="form-control select2"
                                    required data-json = "supplier_id">
                                <option value="">-- Pilih Supplier --</option>
                            </select>
                        </div>

                        <!-- Warehouse -->
                        <div class="col-md-4">
                            <label>Warehouse</label>
                            <select name="warehouse_id" id="warehouse_id"
                                    class="form-control select2"
                                    required data-json="warehouse_id">
                                <option value="">-- Pilih Warehouse --</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-4">
                            <label>Tanggal</label>
                            <input type="date"
                                   name="date"
                                   class="form-control"
                                   value="<?= date('Y-m-d') ?>" data-json="created_at">
                        </div>
                    </div>

                    <!-- TABLE ITEMS -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tblBarangMasuk" style="width:100%;border:1px solid #e2e2e2;">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th width="30%">Produk</th>
                                    <th width="15%">Qty</th>
                                    <th width="20%">Harga Beli</th>
                                    <th width="20%">Subtotal</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                                    <td>
                                        <input type="text"
                                               id="bm_total_display"
                                               class="form-control text-right"
                                               readonly>
                                        <input type="hidden"
                                               name="total"
                                               id="bm_total">
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="button"
                            class="btn btn-primary btn-sm"
                            onclick="addBarangMasukRow()">
                        + Tambah Item
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success"
                            onclick="insertFormdata('frm')">
                        Simpan
                    </button>
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            onclick="resetBarangMasuk()">
                        Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>