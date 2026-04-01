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
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" onclick="loadcbExpCategories()" class="btn btn-info btn-sm pull pull-right">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button>
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>TANGGAL</th>
                        <th>KATEGORI</th>
                        <th>DESKRIPSI</th>
                        <th>JUMLAH</th>
                        <th>DIBUAT</th>
                        <th>PENGGUNA</th>
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
<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Expense</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=" resetModalForm() ">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_expense" data-hapus="mdl_deleteexpense" data-getID="mdl_getidexpense" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" data-json = "id">
                                    <label class="form-control-label">Tanggal <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="date" name="expense_date" data-json="tanggal">
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">KATEGORI: <span class="tx-danger">*</span></label>
                                    <select id="exp_category" class="form-control mb-2 select2" name="category" data-json="kategori_id"></select>
                                </div>
                            </div><!-- col-6 -->

                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">DESKRIPSI: <span class="tx-danger">*</span></label>
                                    <!-- <input class="form-control" type="text" name="description" data-json="keterangan"> -->
                                    <textarea class="form-control" name="description" data-json="keterangan"></textarea>
                                </div>
                            </div><!-- col-8 -->
                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">JUMLAH: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="amount" data-json="jumlah">
                                </div>
                            </div>
                            
                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="resetModalForm()">Batal</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->