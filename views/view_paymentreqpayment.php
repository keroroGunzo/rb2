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
                        <th>REQUEST NUMBER</th>
                        <th>DESCRIPTION</th>
                        <th>TOTAL PAYMENT REQUEST</th>
                        <th>NOMINAL TRANSFER</th>
                        <th>REKENING ASAL</th>
                        <th>REKENING TUJUAN</th>
                        <th>DISETUJUI OLEH</th>
                        <th>TANGGAL DI SETUJUI</th>
                        <!-- <th>AKSI</th> -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>

<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Job Order</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_joborder" data-hapus="mdl_deletevendor" data-getID="mdl_getidjoborder" action=" javascript:" class="form-horizontal form-bordered">
                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" data-json="id">
                                    <label class="form-control-label">CUSTOMER: <span class="tx-danger">*</span></label>
                                    <select name="customer_id" class="form-control select2" id="customer" data-json="customer_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">INVESTOR: <span class="tx-danger">*</span></label>
                                    <select name="investor_id" class="form-control select2" id="investor" data-json="investor_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div><!-- col-6 -->

                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">STATUS: <span class="tx-danger">*</span></label>
                                    <select class="form-control select2" id="status" name="status" data-json="status">
                                        <option value="Open">Open</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>
                            </div><!-- col-8 -->
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">TANGGAL MULAI: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="start_date" name="start_date" data-json="start_date">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">TANGGAL SELESAI: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="end_date" name="end_date" data-json="end_date">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">DESKRIPSI: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="description" data-json="description">
                                </div>
                            </div>
                            <!-- detail item job order -->
                            <div class="col-lg-12">
                                <div class="form-group mg-b-10-force">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><label>ITEM JOB ORDER:</label></h6>
                                    <div class="item-container">
                                        <table id="tblItems" class="table table-bordered table-sm">
                                            <thead class="thead-colored thead-indigo">
                                                <tr>
                                                    <th>ITEM</th>
                                                    <th>HARGA BELI</th>
                                                    <th>QTY</th>
                                                    <th>HARGA JUAL</th>
                                                    <th>TOTAL</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- baris item dinamis -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addItemRow()">+ Tambah Item</button>

                                </div>
                            </div>
                        </div><!-- row -->
                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div><!-- form-layout-footer -->
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->
<script>
    $('#start_date, #end_date').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 2,
        dateFormat: "yy-mm-dd",
        autoclose: true,
        todayHighlight: true,
        beforeShow: function(input, inst) {
            $(inst.dpDiv).css('z-index', 999999); // pastiin di atas modal
        }
    });
</script>