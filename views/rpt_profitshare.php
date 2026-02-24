<?php
session_start();
if ($_SESSION['role'] == "") {
    header("location:notfound.php");
    exit;
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
                    <label>Profit Type</label>
                    <select id="profit_type" class="form-control">
                        <option value="">Semua</option>
                        <option value="investor">Investor</option>
                        <option value="tonase">Tonase</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnFilter">Tampilkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3 text-uppercase tx-bold">Laporan Profit Sharing</h6>
            <table class="table table-bordered table-striped" id="tblReport">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>JO Number</th>
                        <th>Investor</th>
                        <th>Profit Type</th>
                        <th>Tonase</th>
                        <th>Invoice Total</th>
                        <th>Thoriq</th>
                        <th>Imron</th>
                        <th>Marketing</th>
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

<script>
    function loadProfitReport() {
        const start = $("#start").val();
        const end = $("#end").val();
        const profit_type = $("#profit_type").val();

        $.getJSON("models/mdl_report_profitsharing.php", {
            start,
            end,
            profit_type
        }, function(res) {
            if (!res.success) {
                alert("Gagal ambil data!");
                return;
            }

            const tbody = $("#tblReport tbody");
            tbody.empty();

            res.data.forEach((row, i) => {
                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${row.jo_number}</td>
                    <td>${row.investor_name ?? '-'}</td>
                    <td>${row.profit_type}</td>
                    <td>${row.tonase}</td>
                    <td>Rp ${Number(row.invoice_total).toLocaleString("id-ID")}</td>
                    <td>Rp ${Number(row.thoriq_share).toLocaleString("id-ID")}</td>
                    <td>Rp ${Number(row.imron_share).toLocaleString("id-ID")}</td>
                    <td>Rp ${Number(row.marketing_share).toLocaleString("id-ID")}</td>
                </tr>
            `);
            });
        });
    }

    $("#btnFilter").click(loadProfitReport);

    $("#btnExport").click(() => {
        const start = $("#start").val();
        const end = $("#end").val();
        const profit_type = $("#profit_type").val();
        window.open(`models/export_profitsharing_excel.php?start=${start}&end=${end}&profit_type=${profit_type}`);
    });
</script>