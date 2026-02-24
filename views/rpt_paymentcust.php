<?php
session_start();
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

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btnFilter">Tampilkan</button>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-success w-100" id="btnExport">📤 Export Excel</button>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table id="tblReport" class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>JO Number</th>
                        <th>Customer</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Rek Tobaqon</th>
                        <th>Bank Tobaqon</th>
                        <th>Rek Customer</th>
                        <th>Bank Customer</th>
                        <th>Deskripsi</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function loadReport() {
        const start = $("#start").val();
        const end = $("#end").val();

        $.getJSON("models/mdl_report_payment.php", {
            start,
            end
        }, function(res) {
            if (!res.success) return alert("Gagal ambil data!");

            const tbody = $("#tblReport tbody");
            tbody.empty();

            let total = 0;

            res.data.forEach((row, i) => {
                total += parseFloat(row.amount ?? 0);

                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${row.invoice_number ?? '-'}</td>
                    <td>${row.jo_number ?? '-'}</td>
                    <td>${row.nama_cust ?? '-'}</td>
                    <td>${row.payment_date ?? '-'}</td>
                    <td>${row.method ?? '-'}</td>
                    <td>${row.tobaqon_acc_no ?? '-'}</td>
                    <td>${row.tobaqon_bank_name ?? '-'}</td>
                    <td>${row.cust_acc_no ?? '-'}</td>
                    <td>${row.cust_bank_name ?? '-'}</td>
                    <td>${row.payment_desc ?? '-'}</td>
                    <td>Rp ${Number(row.amount).toLocaleString("id-ID")}</td>
                </tr>
            `);
            });

            tbody.append(`
            <tr class="table-light fw-bold">
                <td colspan="11" class="text-end">TOTAL</td>
                <td>Rp ${total.toLocaleString("id-ID")}</td>
                <td colspan="5"></td>
            </tr>
        `);
        });
    }
    $("#btnFilter").click(loadReport);

    $("#btnExport").click(() => {
        const start = $("#start").val();
        const end = $("#end").val();
        window.open(`models/export_report_payment_customer.php?start=${start}&end=${end}`);
    });
</script>