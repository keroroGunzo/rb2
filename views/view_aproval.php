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
                        <!-- <th>No</th> -->
                        <th>ID</th>
                        <th>REQUEST NUMBER</th>
                        <th>JO NUMBER</th>
                        <th>CUSTOMER NAME</th>
                        <th>DESKRIPSI</th>
                        <th>ITEM REQUEST</th>
                        <th>REQUEST DATE</th>
                        <th>AMMOUNT (Rp)</th>
                        <th>STATUS</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Approval Payment Request</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm"
                data-models="mdl_approvepaymentreq"
                data-getID="mdl_getidpaymentreq"
                action="javascript:" class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">

                            <input type="hidden" name="id" id="id" data-json="id">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">NO REQUEST:</label>
                                    <input class="form-control" type="text" name="request_number" data-json="request_number" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">TANGGAL REQUEST:</label>
                                    <input class="form-control" type="text" name="request_date" data-json="request_date" readonly>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">DESKRIPSI:</label>
                                    <input class="form-control" type="text" name="description" data-json="description" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">TOTAL AMOUNT:</label>
                                    <input class="form-control" type="text" name="amount" data-json="amount" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">STATUS APPROVAL:</label>
                                    <select class="form-control select2" name="status" data-json = "status">
                                        <option value="approved">approved</option>
                                        <option value="rejected">rejected</option>
                                        <option value="pending">pending</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">REKENING ASAL:</label>
                                    <select class="form-control select2" name="rek_asal" id="rek_tobaqon">
                                        <!-- load rekening dari DB -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">REKENING TUJUAN:</label>
                                    <select class="form-control select2" name="rek_tuju" id="rek_vendor" data-json = rek_tujuan>
                                        <!-- load rekening dari DB -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">CATATAN:</label>
                                    <textarea class="form-control" name="catatan"></textarea>
                                </div>
                            </div>

                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="clearInput()">Batal</button>
                        </div>
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div>
</div>
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