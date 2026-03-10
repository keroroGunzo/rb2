<?php
require '../config/init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
//include '../views/pageheader.php';
?>
<div class="br-pagebody" style="margin-top: 100px;">
    <div class="br-section-wrapper">

        <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-20">
            POS Penjualan
        </h6>

        <div class="row">

            <!-- PANEL KIRI -->
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <label>Scan Barcode / Cari Produk</label>

                            <div class="input-group">

                                <input type="text"
                                    id="scan_barcode"
                                    class="form-control"
                                    placeholder="Scan barcode atau ketik nama produk">

                                <div class="input-group-append">

                                    <button class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#modalProduk">
                                        <i class="fa fa-search"></i> Buka Daftar Produk
                                    </button>

                                </div>

                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tblSale" style="border: 1px solid;border-color: #dadada;">

                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Produk</th>
                                        <th width="12%">Harga</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <!-- PANEL KANAN -->
            <div class="col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="tx-bold">Ringkasan</h5>

                        <hr>
                        <div class="form-group">
                            <label>Metode Pembayaran</label>

                            <select id="sale_payment_method" class="form-control select2">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="debit">Debit / Credit</option>
                            </select>

                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong id="sale_subtotal">0</strong>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Diskon</span>
                            <input type="number"
                                id="sale_discount"
                                class="form-control form-control-sm"
                                value="0"
                                style="width:100px">
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="tx-bold">Grand Total</span>
                            <h4 id="sale_grandtotal">0</h4>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Bayar</label>
                            <input type="number"
                                id="sale_pay"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text"
                                id="sale_change"
                                class="form-control"
                                readonly>
                        </div>

                        <button class="btn btn-success btn-block"
                            onclick="saveSale()">
                            Simpan Transaksi
                        </button>
                        <iframe id="printFrame" style="display:none;"></iframe>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- Modal Daftar Produk -->
<div class="modal fade" id="modalProduk">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Produk</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="row" id="productGrid">

                    <!-- produk akan dimasukkan via JS -->

                </div>

            </div>

        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        $('#scan_barcode').focus();
    });
</script>