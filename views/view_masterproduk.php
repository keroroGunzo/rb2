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
                        <th>SKU</th>
                        <th>BARCODE</th>
                        <th>NAMA PRODUK</th>
                        <th>HARGA RETAIL</th>
                        <th>HARGA GROSIR</th>
                        <th>JUMLAH MINIMAL GROSIR</th>
                        <th>HARGA BELI (Cost)</th>
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
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Master Produk</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="" id="clossed">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frm" name="frm" data-models="mdl_masterproduk" data-hapus="mdl_deleteproduk" data-getid="mdl_getidmasterproduk" action=" javascript:" class="form-horizontal form-bordered">
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