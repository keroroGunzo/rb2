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
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right" onclick="setStatusInsert();resetformjo()">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button>
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>NOMOR REQUEST</th>
                        <th>NOMOR JO</th>
                        <th>CUSTOMER</th>
                        <th>DESKRIPSI</th>
                        <th>JUMLAH</th>
                        <th>STATUS</th>
                        <th>TANGGAL REQUEST</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div><!-- table-wrapper -->
    </div>
</div>

<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Payment Request</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearInput()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm" data-models="mdl_paymentreq" data-hapus="mdl_deletepaymentreq" data-getid="mdl_getidpaymentreq" action=" javascript:" class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">

                            <!-- Hidden ID -->
                            <input type="hidden" name="id" id="id" data-json="id">

                            <!-- Job Order -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Job Order: <span class="tx-danger">*</span></label>
                                    <select name="jo_number" id="jo_number" class="form-control select2" data-json="jo_number">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div>

                            <!-- Tanggal Request -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Request: <span class="tx-danger">*</span></label>
                                    <input type="text" class="form-control fc-datepicker" name="tanggal_request"
                                        id="tanggal_request" data-json="request_date">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="description" id="description" data-json="description">
                                </div>
                            </div>

                            <!-- Detail Item -->
                            <div class="col-lg-12">
                                <div class="form-group mg-b-10-force">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">
                                        <label>ITEM REQUEST:</label>
                                    </h6>
                                    <div class="item-container">
                                        <table id="tblItems" class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>PILIH</th> <!-- ✅ kolom baru -->
                                                    <th>ITEM</th>
                                                    <th>HARGA</th>
                                                    <th>QTY</th>
                                                    <th>TAX</th>
                                                    <th>NOMINAL TAX</th>
                                                    <th>TOTAL</th>
                                                    <th>AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- baris item dinamis -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="4" class="text-right">GRAND TOTAL</th>
                                                    <th>
                                                        <input type="text" name="grand_total" class="form-control" id="grandTotal" readonly>
                                                        <!-- <input type="text" name="grand_total" id="grand_total_hidden"> -->
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- <button type="button" class="btn btn-success btn-sm" onclick="addItemRow()">+ Tambah Item</button> -->
                                </div>
                            </div>
                        </div><!-- row -->
                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData"
                                onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="clearInput()">Batal</button>
                        </div>
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#tanggal_request').datepicker({
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