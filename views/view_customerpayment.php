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
                        <th>INVOICE NUMBER</th>
                        <th>JO NUMBER</th>
                        <th>METODE TRANSAKSI</th>
                        <th>CUSTOMER</th>
                        <th>PAYMENT DATE</th>
                        <th>AMOUNT</th>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Form Pembayaran Customers</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearInput()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="frmPayment" name="frmPayment"
                data-models="mdl_customerpayment"
                action="javascript:"
                class="form-horizontal form-bordered">

                <div class="modal-body pd-25">
                    <div class="form-layout">
                        <div class="row mg-b-25">

                            <!-- hidden id invoice -->
                            <input type="hidden" name="invoice_id" id="invoice_id" data-json="invoice_id">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Nomor Invoice:</label>
                                    <select class="form-control select2" name="invoice_number" id="invoice_number" required>
                                        <option value="">-- Pilih Invoice --</option>
                                        <!-- opsi invoice akan diload via AJAX -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Pembayaran:</label>
                                    <input class="form-control fc-datepicker" type="text" id="payment_date" name="payment_date">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Jumlah Bayar (Rp):</label>
                                    <!-- <input class="form-control" type="number" id="amount" name="amount" min="1"> -->
                                    <input class="form-control" type="text" id="amount" name="amount" inputmode="decimal" placeholder="Masukkan jumlah bayar">

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Metode Pembayaran:</label>
                                    <select class="form-control select2" name="method" id="method">
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">No Referensi:</label>
                                    <input class="form-control" type="text" name="reference_no" id="reference_no">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">Rekening Customer:</label>
                                    <select class="form-control select2" name="customer_account" id="rekening_cust" required>
                                        <option value="">-- Pilih Rekening --</option>

                                    </select>
                                    <!-- <input class="form-control" type="text" name="customer_account" id="rekening_cust" required> -->
                                </div>
                            </div>

                            <div class="col-lg-6 bank-account-wrapper">
                                <div class="form-group">
                                    <label class="form-control-label">Rekening Perusahaan:</label>
                                    <select name="company_account" class="form-control select2" id="rekening_tobaqon">
                                        <!-- nanti isi dari master rekening perusahaan -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Keterangan:</label>
                                    <input class="form-control" type="text" name="description">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-info" id="invoiceInfoBox" style="display:none;">
                                    <p><strong>Total Invoice:</strong> <span id="infoTotal"></span></p>
                                    <p><strong>Sudah Dibayar:</strong> <span id="infoPaid"></span></p>
                                    <p><strong>Sisa Tagihan:</strong> <span id="infoOutstanding"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-layout-footer">
                            <button class="btn btn-info" id="simpanPayment"
                                onclick="insertFormdata('frmPayment')">Simpan</button>
                            <button class="btn btn-secondary" data-dismiss="modal" onclick="clearInput()">Batal</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#modal').on('shown.bs.modal', function() {
            const input = document.getElementById("amount");
            if (!input) return;

            // --- Fungsi Format Rupiah (2 digit desimal) ---
            function formatRupiah(value) {
                if (!value) return "";
                // Hanya angka dan koma
                value = value.replace(/[^\d,]/g, "");

                // Pisahkan bagian desimal
                let parts = value.split(",");
                let integerPart = parts[0];
                let decimalPart = "";

                if (parts.length > 1) {
                    // Ambil hanya 2 digit desimal
                    decimalPart = "," + parts[1].substring(0, 2);
                }

                // Tambah titik setiap 3 digit dari belakang
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                return integerPart + decimalPart;
            }

            // Hapus event lama biar gak dobel
            input.removeEventListener("input", input._rupiahHandler || (() => {}));

            const handler = function() {
                const start = this.selectionStart;
                const oldLength = this.value.length;
                this.value = formatRupiah(this.value);
                const newLength = this.value.length;
                this.setSelectionRange(start + (newLength - oldLength), start + (newLength - oldLength));
            };

            input.addEventListener("input", handler);
            input._rupiahHandler = handler;

            // Sebelum submit → ubah jadi angka murni
            const form = input.closest("form");
            if (form && !form._rupiahSubmitHandler) {
                form.addEventListener("submit", function() {
                    let val = input.value;
                    val = val.replace(/\./g, ""); // hapus titik ribuan
                    val = val.replace(",", "."); // ubah koma jadi titik
                    input.value = val; // contoh: 27.204.766,47 → 27204766.47
                });
                form._rupiahSubmitHandler = true;
            }
        });

        // === Datepicker tetap jalan ===
        $('#payment_date').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            numberOfMonths: 1,
            dateFormat: "yy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            beforeShow: function(input, inst) {
                $(inst.dpDiv).css('z-index', 999999);
            }
        });
    });
</script>