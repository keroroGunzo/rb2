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
            <button type="button" id="add_button" data-toggle="modal" data-target="#modal" class="btn btn-info btn-sm pull pull-right" onclick="setStatusInsert();loadcbrekcust();loadcbinvoice();loadcbrektobaqon()">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button>
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>JO NUMBER</th>
                        <th>JO DESC</th>
                        <th>EXPENSE TYPE</th>
                        <th>EXPENSE DESC</th>
                        <th>RECEIPT NUMBER</th>
                        <th>AMOUNT</th>
                        <th>EXPENSE DATE</th>
                        <th>CREATED BY</th>
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

<div id="modal" class="modal fade effect-super-scaled" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Job Order Expense</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm"
                data-models="mdl_joborder_expense"
                data-getid="mdl_getidjoborder_expense"
                action="javascript:"
                class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">

                            <input type="hidden" name="id" id="id" data-json="id">

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Job Order: <span class="tx-danger">*</span></label>
                                    <select name="job_order_id" id="job_order_id" class="form-control select2" data-json="job_order_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Jenis Pengeluaran: <span class="tx-danger">*</span></label>
                                    <select name="expense_type" id="expense_type" class="form-control" data-json="expense_type">
                                        <option value="insentif">Insentif</option>
                                        <option value="bbm">BBM</option>
                                        <option value="materai">Materai</option>
                                        <option value="operasional">Operasional</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi:</label>
                                    <input type="text" name="description" id="description" class="form-control" data-json="description">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Nomor Bukti / Nota:</label>
                                    <input type="text" name="receipt_number" id="receipt_number" class="form-control" data-json="receipt_number">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal:</label>
                                    <input type="date" name="expense_date" id="expense_date" class="form-control" data-json="expense_date">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Nominal (Rp): <span class="tx-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control" min="0" step="0.01" data-json="amount">
                                </div>
                            </div>

                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData" onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#expense_date').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        dateFormat: "yy-mm-dd",
        autoclose: true,
        todayHighlight: true,
        beforeShow: function(input, inst) {
            $(inst.dpDiv).css('z-index', 999999); // pastiin di atas modal
        }
    });
</script>