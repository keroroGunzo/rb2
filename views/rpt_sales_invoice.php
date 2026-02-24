<?php
session_start();
// cek apakah yang mengakses halaman ini sudah login
if ($_SESSION['role'] == "") {
    header("location:notfound.php");
}
include '../views/pageheader.php';
?>
<div class="br-pagebody">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" id="start" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" id="end" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select id="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="paid">Lunas</option>
                        <option value="partial">Sebagian</option>
                        <option value="open">Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Customer</label>
                    <select id="customer_id" class="form-control">
                        <option value="">Semua Customer</option>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnFilter">Tampilkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 text-uppercase tx-bold">Laporan Invoice</h6>
            <table class="table table-bordered table-striped" id="tblReport">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="text-end mt-3">
                <button class="btn btn-success" id="btnExport">📤 Export Excel</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function loadReport() {
        const start = $("#start").val();
        const end = $("#end").val();
        const status = $("#status").val();
        const customer_id = $("#customer_id").val(); // 🔑 AMBIL CUSTOMER

        $.getJSON("models/mdl_report_salesinvoice.php", {
            start,
            end,
            status,
            customer_id // 🔑 KIRIM KE BACKEND
        }, function(res) {
            if (!res.success) return alert("Gagal ambil data!");

            const tbody = $("#tblReport tbody");
            tbody.empty();

            let total = 0;
            res.data.forEach((row, i) => {
                total += parseFloat(row.total_amount);
                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${row.invoice_number}</td>
                    <td>${row.invoice_date}</td>
                    <td>${row.customer_name ?? '-'}</td>
                    <td>Rp ${Number(row.total_amount).toLocaleString("id-ID")}</td>
                    <td>${row.status}</td>
                </tr>
            `);
            });

            tbody.append(`
            <tr class="fw-bold table-light">
                <td colspan="4" class="text-end">Total</td>
                <td colspan="2">Rp ${total.toLocaleString("id-ID")}</td>
            </tr>
        `);
        });
    }


    $("#btnFilter").click(loadReport);

    $("#btnExport").click(() => {
        const start = $("#start").val();
        const end = $("#end").val();
        const status = $("#status").val();
        const customer_id = $("#customer_id").val();

        window.open(
            `models/export_sales_excel.php?start=${start}&end=${end}&status=${status}&customer_id=${customer_id}`
        );
    });
</script>