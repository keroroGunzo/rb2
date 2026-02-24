<?php
session_start();
if (empty($_SESSION['role'])) {
    header("location:notfound.php");
    exit;
}
include '../views/pageheader.php';
?>

<div class="br-pagebody">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <label>Dari Tanggal</label>
                    <input type="date" id="start" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Sampai Tanggal</label>
                    <input type="date" id="end" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnFilter">Tampilkan</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <h5 class="mb-3">Ringkasan Laba Rugi</h5>

            <table class="table table-bordered">
                <tr>
                    <th width="40%">Pendapatan (Revenue)</th>
                    <td id="rev">Rp 0</td>
                </tr>
                <tr>
                    <th>HPP (Costable)</th>
                    <td id="hpp">Rp 0</td>
                </tr>
                <tr class="table-warning">
                    <th>Laba Kotor (Gross Profit)</th>
                    <td id="gross">Rp 0</td>
                </tr>
                <tr>
                    <th>Pengeluaran Operasional</th>
                    <td id="exp">Rp 0</td>
                </tr>
                <tr class="table-info">
                    <th>Laba Bersih (Net Profit)</th>
                    <td id="net">Rp 0</td>
                </tr>
            </table>

            <h5 class="mt-4">Profit Sharing</h5>
            <table class="table table-bordered mt-2">
                <tr>
                    <th>Thoriq</th>
                    <td id="ps_thoriq">Rp 0</td>
                </tr>
                <tr>
                    <th>Imron</th>
                    <td id="ps_imron">Rp 0</td>
                </tr>
                <tr>
                    <th>Marketing</th>
                    <td id="ps_marketing">Rp 0</td>
                </tr>
            </table>

            <div class="text-end mt-3">
                <button class="btn btn-success" id="btnExport">📤 Export Excel</button>
            </div>

        </div>
    </div>

</div>

<script>
    function loadReport() {
        const start = $("#start").val();
        const end = $("#end").val();

        $.getJSON("models/mdl_report_labarugi.php", {
            start,
            end
        }, function(res) {
            if (!res.success) return alert("Gagal ambil data!");

            let d = res.data;

            $("#rev").text("Rp " + d.revenue.toLocaleString("id-ID"));
            $("#hpp").text("Rp " + d.hpp.toLocaleString("id-ID"));
            $("#gross").text("Rp " + d.gross_profit.toLocaleString("id-ID"));
            $("#exp").text("Rp " + d.expenses.toLocaleString("id-ID"));
            $("#net").text("Rp " + d.net_profit.toLocaleString("id-ID"));

            $("#ps_thoriq").text("Rp " + d.profit_sharing.thoriq.toLocaleString("id-ID"));
            $("#ps_imron").text("Rp " + d.profit_sharing.imron.toLocaleString("id-ID"));
            $("#ps_marketing").text("Rp " + d.profit_sharing.marketing.toLocaleString("id-ID"));
        });
    }

    $("#btnFilter").click(loadReport);

    $("#btnExport").click(() => {
        const start = $("#start").val();
        const end = $("#end").val();

        window.open(`models/export_labarugi_excel.php?start=${start}&end=${end}`);
    });
</script>