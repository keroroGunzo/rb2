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
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data </button>
            <br /> <br />
            <table id="datatable1" class="table display responsive data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>NAMA</th>
                        <th>TELP</th>
                        <th>DISCOUNT (%)</th>
                        <th>CREATED AT</th>
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
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Master Data Member</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetModalForm()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_mastermember" data-hapus="mdl_deletemember" data-getID="mdl_getidmember" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" data-json = "id">
                                    <label class="form-control-label">NAMA <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" data-json="name">
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">TELP: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="phone" data-json="phone">
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">DISCOUNT: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="discount_percent" data-json="discount_percent">
                                </div>
                            </div><!-- col-6 -->                            
                        </div><!-- row -->
                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="resetModalForm() ">Batal</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->