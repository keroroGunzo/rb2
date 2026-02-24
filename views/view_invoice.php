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
                        <th>INVOICE NUMBER</th>
                        <th>JO NUMBER</th>
                        <th>CUSTOMER</th>
                        <th>TANGGAL INVOICE</th>
                        <th>DUE DATE</th>
                        <th>AMOUNT</th>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Invoice</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" id="frm" name="frm"
                data-models="mdl_invoice"
                data-hapus="mdl_delete_invoice"
                data-getID="mdl_getidinvoice"
                action="javascript:"
                class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">

                            <input type="hidden" name="id" id="id" data-json="invoice_id">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Job Order: <span class="tx-danger">*</span></label>
                                    <select name="job_order_id" class="form-control select2" id="job_order_id" data-json="job_order_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Invoice: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="invoice_date" name="invoice_date" data-json="invoice_date">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Jatuh Tempo: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="due_date" name="due_date" data-json="due_date">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Deskripsi:</label>
                                    <input class="form-control" type="text" name="description" data-json="description">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Reference:</label>
                                    <input class="form-control" type="text" name="reference" data-json="reference">
                                </div>
                            </div>
                            <!-- ✅ Tambahan radio button untuk Reimbursement -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label d-block">Jenis Invoice:</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="normal_invoice" name="reimbuse" value="0" class="custom-control-input" data-json="reimbuse" checked>
                                        <label class="custom-control-label" for="normal_invoice">Normal</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="reimburse_invoice" name="reimbuse" value="1" class="custom-control-input" data-json="reimbuse">
                                        <label class="custom-control-label" for="reimburse_invoice">Reimbursement</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detail Item Invoice -->
                            <div class="col-lg-12">
                                <div class="form-group mg-b-10-force">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">
                                        <label>Item Invoice:</label>
                                    </h6>
                                    <div class="item-container">
                                        <table id="tblItems" class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>ITEM</th>
                                                    <th>DESKRIPSI</th>
                                                    <th style="width: 10%;">QTY</th>
                                                    <th style="width: 10%;">HARGA</th>
                                                    <th style="width: 10%;">TAX</th>
                                                    <th style="width: 20%;">HARGA (+TAX)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- baris item dinamis -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>Sub Total:</strong></td>
                                                    <td>
                                                        <input type="text" id="subTotalDisplay" class="form-control text-right" readonly>
                                                        <input type="hidden" id="subTotal" name="subtotal">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>Tax (%) :</strong></td>
                                                    <td>
                                                        <input type="number" id="taxRate" name="tax_rate_hdr" class="form-control text-right" step="any" min="0" data-json="tax_rate_hdr">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>Sub Total + Taxes:</strong></td>
                                                    <td>
                                                        <input type="text" id="grandTotalDisplay" class="form-control text-right" readonly data-json="total_amount">
                                                        <input type="hidden" id="grandTotal" name="total_amount" data-json="total_amount">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>PPH (%) :</strong></td>
                                                    <td>
                                                        <input type="number" id="pph" name="pph" class="form-control text-right" step="any" min="0" data-json="pph_rate">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>Deposit :</strong></td>
                                                    <td>
                                                        <input type="number" id="deposit" name="deposit" class="form-control text-right" step="any" min="0" data-json="deposit">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                                                    <td>
                                                        <input type="text" id="final_amount_display" class="form-control text-right" readonly>
                                                        <input type="hidden" id="final_amount" name="final_amount">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- row -->

                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanData" onclick="insertFormdata('frm')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="clearInput()">Batal</button>
                        </div>
                    </div><!-- form-layout -->
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#invoice_date, #due_date').datepicker({
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