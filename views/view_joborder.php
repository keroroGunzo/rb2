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
            <button type="button" id="add_button" data-target="#modal" class="btn btn-info btn-sm pull pull-right">
                <span class="glyphicon glyphicon-plus-sign"></span> Tambah Data</button>
            <br /> <br />
            <table id="datatable1" class="table display responsive nowrap data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>JO NUMBER</th>
                        <th>CUSTOMER</th>
                        <th>INVESTOR</th>
                        <th>DESKRIPSI</th>
                        <th>START DATE</th>
                        <th>END DATE</th>
                        <th>STATUS</th>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Job Order</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="" id="clossed">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_joborder" data-hapus="mdl_deletejo" data-getid="mdl_getidjoborder" action=" javascript:" class="form-horizontal form-bordered">
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
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">INVESTOR: <span class="tx-danger">*</span></label>
                                    <select name="investor_id" class="form-control select2" id="investor" data-json="investor_id">
                                        <!-- load dari DB -->
                                    </select>
                                </div>
                            </div><!-- col-6 -->
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">VESSEL: <span class="tx-danger">*</span></label>
                                    <input type="text" class="form-control" name="vessel" data-json="vessel">
                                </div>
                            </div><!-- col-6 -->
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
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">DESKRIPSI: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="description" data-json="description">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">POL / POD: <span class="tx-danger">*</span></label>
                                    <input class="form-control" type="text" name="polpod" data-json="polpod">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group" id="profit_investor_wrap">
                                    <label class="form-control-label">PROFIT TYPE: <span class="tx-danger">*</span></label>
                                    <label class="rdiobox">
                                        <input type="radio" name="profit_type" value="investor" checked>
                                        <span>Investor (70:30 / 50:50)</span>
                                    </label>

                                    <label class="rdiobox">
                                        <input type="radio" name="profit_type" value="tonase">
                                        <span>Tonase</span>
                                    </label>
                                </div>
                            </div><!-- col-3 -->
                            <div class="col-lg-3">
                                <div class="form-group" id="profit_non_tonase_wrap">
                                    <label class="form-control-label">PROFIT TYPE: <span class="tx-danger">*</span></label>
                                    <label class="rdiobox">
                                        <input type="radio" name="profit_type" value="non_tonase">
                                        <span>Investor (100% Thoriq)</span>
                                    </label>

                                </div>
                            </div><!-- col-3 -->
                            <div class="col-lg-3" id="tonase_section" style="display:none;">
                                <div class="form-group">
                                    <label class="form-control-label">TONASE (TON):</label>
                                    <input type="number" step="0.01" class="form-control" name="tonase" id="tonase" placeholder="Masukkan jumlah ton" data-json="tonase">
                                </div>
                            </div>
                            <div class="col-lg-3" id="rate_section" style="display:none;">
                                <div class="form-group">
                                    <label class="form-control-label">RATE (TON):</label>
                                    <input type="number" step="0.01" class="form-control" name="rate" id="rate" placeholder="Masukkan rate tonase" data-json="rate">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">ETA: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="eta" name="eta" data-json="eta">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">ETD: <span class="tx-danger">*</span></label>
                                    <input class="form-control fc-datepicker" type="text" id="etd" name="etd" data-json="etd">
                                </div>
                            </div>
                            <!-- detail item job order -->
                            <div class="col-lg-12">
                                <div class="form-group mg-b-10-force">
                                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><label>ITEM JOB ORDER:</label></h6>
                                    <div class="item-container">
                                        <table id="tblItems" class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 250px;">ITEM</th>
                                                    <th>HARGA BELI</th>
                                                    <th style="width: 100px">QTY</th>
                                                    <th style="width: 200px">HARGA JUAL</th>
                                                    <th>TOTAL</th>
                                                    <th>COST</th>
                                                    <th style=" width: 100px;">TAX</th>
                                                    <th style="width: 150px;">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- baris item dinamis -->
                                            </tbody>
                                            <tfoot style="border: 1px solid #ddd;">
                                                <tr>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right"><strong>Sub Total:</strong></td>
                                                    <td>
                                                        <input type="text" id="jo_subTotalDisplay" class="form-control text-right" readonly>
                                                        <input type="hidden" id="jo_subTotal" name="subtotal">
                                                    </td>
                                                </tr>

                                                <tr>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right"><strong>Grand Total (incl. Tax):</strong></td>
                                                    <td>
                                                        <input type="text" id="jo_grandTotalDisplay" class="form-control text-right" readonly>
                                                        <input type="hidden" id="jo_grandTotal" name="total_amount" data-json="total_amount">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm" id="btnTambahItem" onclick="addItemRow()">+ Tambah Item</button>
                                </div>
                            </div>
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
<script>
    $('#start_date, #end_date, #eta,#etd').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        numberOfMonths: 1,
        dateFormat: "dd-mm-yy",
        autoclose: true,
        todayHighlight: true,
        beforeShow: function(input, inst) {
            $(inst.dpDiv).css('z-index', 999999); // pastiin di atas modal
        }
    });

    
</script>