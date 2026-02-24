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

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">Laporan Laba Rugi Per Job Order</h5>

            <table id="tblJo" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>JO Number</th>
                        <th>Customer</th>
                        <th>Investor</th>
                        <th>Revenue Gross</th>
                        <th>PPN</th>
                        <th>PPh</th>
                        <th>Revenue Net</th>
                        <th>Reimburse</th>
                        <th>Cost</th>
                        <th>Expenses</th>
                        <th>Gross Profit</th>
                        <th>Net Profit</th>
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
    var tbl;

    // ===== FORMAT RUPIAH BIASA (ANGKA) =====
    function rupiah(d) {
        const n = Number(d);
        return "Rp " + (isNaN(n) ? "0" : n.toLocaleString("id-ID"));
    }

    // ===== FORMAT RUPIAH + PERSENTASE (OBJECT) =====
    function rupiahWithPercent(obj) {
        if (!obj || typeof obj !== "object") {
            return "Rp 0 (0%)";
        }

        const amt = Number(obj.amount || 0);
        const pct = Number(obj.percent || 0);

        return "Rp " + amt.toLocaleString("id-ID") + " (" + pct + "%)";
    }

    function loadReport() {
        const start = $("#start").val();
        const end   = $("#end").val();

        if (tbl) tbl.destroy();

        tbl = $("#tblJo").DataTable({
            ajax: {
                url: "models/mdl_report_labarugi_per_jo.php",
                data: { start, end },
                dataSrc: "data"
            },
            columns: [
                { data: "no" },
                { data: "jo_number" },
                { data: "customer" },
                { data: "investor" },

                // ===== REVENUE =====
                { data: "revenue_gross", render: rupiah },
                { data: "tax_amount",    render: rupiah },
                { data: "pph_amount",    render: rupiah },
                { data: "revenue_net",   render: rupiah },

                // ===== BIAYA =====
                { data: "reimburse", render: rupiah },
                { data: "hpp",       render: rupiah },
                { data: "expenses",  render: rupiah },

                // ===== PROFIT =====
                { data: "gross_profit", render: rupiah },
                { data: "net_profit",   render: rupiah },

                // ===== BAGI HASIL (NOMINAL + %) =====
                { data: "thoriq",    render: d => rupiahWithPercent(d) },
                { data: "imron",     render: d => rupiahWithPercent(d) },
                { data: "marketing", render: d => rupiahWithPercent(d) }
            ]
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity
        });
    }

    $("#btnFilter").click(loadReport);

    $("#btnExport").click(() => {
        const start = $("#start").val();
        const end = $("#end").val();
        window.open(`models/export_labarugi_per_jo.php?start=${start}&end=${end}`);
    });
</script>
<style>
/* FIX DOUBLE SORT ICON – KHUSUS LAPORAN LABA RUGI PER JO */
#tblJo.dataTable thead .sorting:before,
#tblJo.dataTable thead .sorting:after,
#tblJo.dataTable thead .sorting_asc:before,
#tblJo.dataTable thead .sorting_asc:after,
#tblJo.dataTable thead .sorting_desc:before,
#tblJo.dataTable thead .sorting_desc:after {
    display: none !important;
}
</style>
