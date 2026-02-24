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
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right" onclick="setStatusInsert()">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button>
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>VENDOR</th>
                        <th>BANK</th>
                        <th>NO REKENING</th>
                        <th>NAMA REKENING</th>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Master Data Rekening Vendor</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=" clearInput()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_rekvendor" data-hapus="mdl_deleterekvendor" data-getID="mdl_getidrekvendor" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" data-json="id">
                                    <label class="form-control-label">VENDOR <span class="tx-danger">*</span></label>
                                    <select name="vendor_id" class="form-control select2" id="vendor" data-json="vendor_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">NAMA BANK <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="bank_name" data-json="bank_name">
                                </div>
                            </div><!-- col-6 -->

                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">NO. REKENING: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="account_number" data-json="account_number">
                                </div>
                            </div><!-- col-8 -->
                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">NAMA AKUN: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="account_name" data-json="account_name">
                                </div>
                            </div><!-- col-8 -->
                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick=" clearInput()">Batal</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->