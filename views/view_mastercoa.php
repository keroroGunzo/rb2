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
                        <th>CODE</th>
                        <th>COA NAME</th>
                        <th>COA TYPE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>

<!--modals form -->
<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Master Data COA</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearInput()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_mastercoa" data-hapus="mdl_deletecoa" data-getID="mdl_getidcoa" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" data-json = "code">
                                    <label class="form-control-label">KODE COA: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="code" data-json="code">
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">NAMA COA: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="nama_coa" data-json="nama_coa">
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">TYPE COA: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="type" data-json="type">
                                </div>
                            </div><!-- col-8 -->
                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="clearInput()">Batal</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->