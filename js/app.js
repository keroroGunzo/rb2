$(document).ready(function () {
  var table = $(".datatable")
    .removeAttr("width")
    .DataTable({
      scrollY: "500px",
      scrollX: true,
      scrollCollapse: true,
      paging: false,
      columnDefs: [{ width: 50 }],
      fixedColumns: true,
    });
  $(".br-menu-link").click(function () {
    $(".br-menu-link").removeClass("active");
    $("this").addClass("active");
  });
  table.destroy();
  $(".br-menu-sub li a.sub-link").click(function () {
    var halaman = $(this).attr("href");

    $(".br-mainpanel").load(
      BASE_URL + "views/" + halaman + ".php",
      function () {
        console.log("LOADED:", halaman);
        doCallBack(halaman);
      },
    );

    return false;
  });
  var $confModal = $(".modal");
  var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};
  $confModal.on("hidden", function () {
    $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
  });
});
//kosongkan isi form di dalam modal dengan id =  modal
function resetModalForm() {
  let form = $("#modal").find("form");
  if (form.length) form[0].reset();
  $("#id").val("");
}

document.addEventListener("click", function (e) {
  var link = e.target.closest("a[data-getid]");
  if (link) {
    e.preventDefault();
    var models = link.dataset.getid;
    var userId = link.dataset.userid;

    $.ajax({
      url: "models/" + models + ".php",
      method: "POST",
      data: { id: userId },
      dataType: "json",
      success: function (response) {
        if (response && !response.error) {
          for (var key in response) {
            $('[data-json="' + key + '"]').val(response[key]);
          }
        } else {
          alert(response.error || "Data tidak ditemukan");
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
      },
    });
  }
});
//function selected ul li menu samping kiri
function switchChannel(el) {
  // find all the elements in your channel list and loop over them
  Array.prototype.slice
    .call(document.querySelectorAll('ul[data-tag="channelList"] li'))
    .forEach(function (element) {
      // remove the selected class
      element.classList.remove("active");
    });
  // add the selected class to the element that was clicked
  el.classList.add("active");
}

//begin card dashboard
//job order hari ini
function formatCurrency(value) {
  return "Rp " + Number(value || 0).toLocaleString("id-ID");
}

function loadDashboardSummary() {
  $.getJSON("models/mdl_dashboard_summary.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data dashboard:", res.message);
      return;
    }

    const data = res.today;

    $("#jo-today-count").text(data.job_order);
    $("#sales-today").text(formatCurrency(data.sales));
    $("#expense-today").text(formatCurrency(data.expense));
    $("#cost-today").text(formatCurrency(data.cost)); // ✅ ini kunci tambahan
    $("#profit-today").text(formatCurrency(data.profit));
  }).fail(function (xhr) {
    console.error("Error AJAX:", xhr.responseText);
  });
}

function renderMiniWaveChart(canvasId, dataPoints) {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return; // pastikan elemen ada

  const ctx = canvas.getContext("2d");

  // ✅ Pastikan canvas menyesuaikan lebar container
  const parentWidth = canvas.parentElement
    ? canvas.parentElement.offsetWidth
    : 300;
  canvas.width = parentWidth;
  canvas.height = 70;

  // Hapus chart lama (jika ada) biar gak dobel
  if (canvas.chartInstance) {
    canvas.chartInstance.destroy();
  }

  // Buat chart baru
  canvas.chartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
      datasets: [
        {
          data: dataPoints,
          fill: true,
          borderColor: "rgba(255,255,255,0.8)",
          backgroundColor: "rgba(255,255,255,0.3)",
          tension: 0.45, // lebih lembut
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { x: { display: false }, y: { display: false } },
      elements: { point: { radius: 0 } },
    },
  });
}

// $(document).ready(() => {
//   loadDashboardSummary();
//   renderMiniWaveChart("joTodayWaveChart", [4, 6, 3, 7, 8, 5, 9]);
//   renderMiniWaveChart("salesWaveChart", [3, 5, 4, 6, 5, 7, 8]);
//   renderMiniWaveChart("expenseWaveChart", [2, 3, 2, 4, 3, 5, 3]);
//   renderMiniWaveChart("costWaveChart", [2, 5, 3, 6, 4, 7, 5]);
//   renderMiniWaveChart("profitWaveChart", [1, 4, 3, 6, 4, 5, 7]);
//   loadSalesMonthlyChart(); // fungsi baru
//   loadSalesExpenseChart();
//   loadProfitMarginChart();
//   loadTopCustomerChart();
//   loadTonaseChart();
//   loadCustomerRetentionChart();
//   loadCustomerRetentionChart();
//   loadPaymentStatusChart();
// });
$(window).on("resize", function () {
  renderMiniWaveChart("joTodayWaveChart", [4, 6, 3, 7, 8, 5, 9]);
  renderMiniWaveChart("salesWaveChart", [3, 5, 4, 6, 5, 7, 8]);
  renderMiniWaveChart("expenseWaveChart", [2, 3, 2, 4, 3, 5, 3]);
  renderMiniWaveChart("profitWaveChart", [1, 4, 3, 6, 4, 5, 7]);
});
//end job order hari ini

function loadSalesMonthlyChart() {
  $.getJSON("models/mdl_sales_monthly.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data chart:", res.message);
      return;
    }

    const ctx = document.getElementById("ch5").getContext("2d");
    const labels = res.labels.map((m) =>
      new Date(2025, m - 1).toLocaleString("id-ID", { month: "short" }),
    );
    const values = res.values;

    // Update info header
    $("#chart-year").text(res.year);
    $("#total-sales-year").text(
      "Rp " + res.total_sales.toLocaleString("id-ID"),
    );

    // Hapus chart lama kalau ada
    if (window.salesMonthlyChart) window.salesMonthlyChart.destroy();

    window.salesMonthlyChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Sales per Bulan",
            data: values,
            backgroundColor: "rgba(91, 107, 255, 0.7)",
            borderColor: "rgba(91, 107, 255, 1)",
            borderWidth: 1,
            borderRadius: 6,
            hoverBackgroundColor: "rgba(91, 107, 255, 0.9)",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce", // 🎯 efek mantul pas muncul
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => "Rp " + ctx.parsed.y.toLocaleString("id-ID"),
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: "#495057" },
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: "#495057",
              callback: (v) => "Rp " + v.toLocaleString("id-ID"),
            },
          },
        },
      },
    });
  });
}

function loadSalesExpenseChart() {
  $.getJSON("models/mdl_sales_expense_monthly.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data chart:", res.message);
      return;
    }

    const ctx = document.getElementById("chSalesExpense").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: res.months,
        datasets: [
          {
            label: "Sales",
            data: res.sales,
            backgroundColor: "rgba(54, 162, 235, 0.6)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
          },
          {
            label: "Expense (Cost + Harga Beli)",
            data: res.expense,
            backgroundColor: "rgba(255, 99, 132, 0.6)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce", // 🎯 efek mantul pas muncul
        },
        plugins: {
          legend: {
            position: "top",
            labels: { color: "#333" },
          },
          title: {
            display: false,
          },
        },
        scales: {
          x: { ticks: { color: "#555" }, grid: { display: false } },
          y: {
            beginAtZero: true,
            ticks: {
              callback: (value) => "Rp " + value.toLocaleString("id-ID"),
              color: "#555",
            },
          },
        },
      },
    });
  });
}

function loadProfitMarginChart() {
  $.getJSON("models/mdl_profit_monthly.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data profit margin:", res.message);
      return;
    }

    const labels = res.months;
    const salesData = res.sales;
    const expenseData = res.expense;
    const profitData = res.profit;

    new Chart(document.getElementById("chProfitMargin"), {
      type: "line",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Sales",
            data: salesData,
            borderColor: "#28a745",
            borderWidth: 2,
            fill: false,
            tension: 0.3,
          },
          {
            label: "Expense",
            data: expenseData,
            borderColor: "#dc3545",
            borderWidth: 2,
            fill: false,
            tension: 0.3,
          },
          {
            label: "Profit",
            data: profitData,
            borderColor: "#007bff",
            borderWidth: 2,
            fill: false,
            borderDash: [5, 5],
            tension: 0.3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce", // 🎯 efek mantul pas muncul
        },
        plugins: {
          legend: { position: "top" },
          tooltip: {
            callbacks: {
              label: function (ctx) {
                return (
                  ctx.dataset.label +
                  ": Rp " +
                  ctx.parsed.y.toLocaleString("id-ID")
                );
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (value) => "Rp " + value.toLocaleString("id-ID"),
            },
          },
        },
      },
    });
  }).fail((xhr) => console.error("Error AJAX:", xhr.responseText));
}

function loadTopCustomerChart() {
  $.getJSON("models/mdl_top_customer.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data top customer:", res.message);
      return;
    }

    $("#topCustomerYear").text(res.year);

    new Chart(document.getElementById("chTopCustomer"), {
      type: "bar",
      data: {
        labels: res.customers,
        datasets: [
          {
            label: "Total Sales",
            data: res.totals,
            backgroundColor: [
              "#007bff",
              "#28a745",
              "#ffc107",
              "#17a2b8",
              "#dc3545",
            ],
          },
        ],
      },
      options: {
        indexAxis: "y", // horizontal bar
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce", // 🎯 efek mantul pas muncul
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => "Rp " + ctx.parsed.x.toLocaleString("id-ID"),
            },
          },
        },
        scales: {
          x: {
            beginAtZero: true,
            ticks: {
              callback: (value) => "Rp " + value.toLocaleString("id-ID"),
            },
          },
        },
      },
    });
  }).fail((xhr) => console.error("Error AJAX:", xhr.responseText));
}

function loadTonaseChart() {
  $.getJSON("models/mdl_tonase_monthly.php", function (res) {
    if (!res.success) return console.error(res.message);

    new Chart(document.getElementById("chTonaseMonthly"), {
      type: "bar",
      data: {
        labels: res.months,
        datasets: [
          {
            label: "Total Tonase (kg)",
            data: res.tonase,
            backgroundColor: "#17a2b8",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce", // 🎯 efek mantul pas muncul
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) =>
                ctx.dataset.label +
                ": " +
                ctx.parsed.y.toLocaleString("id-ID") +
                " kg",
            },
          },
        },
        scales: {
          y: { beginAtZero: true },
        },
      },
    });
  });
}
let customerRetentionChart = null;

function loadCustomerRetentionChart() {
  $.getJSON("models/mdl_customer_retention.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data customer retention:", res.message);
      return;
    }

    $("#year-retention").text(res.year);
    if (res.customers.length === 0) return;

    const canvas = document.getElementById("chCustomerRetention");
    const ctx = canvas.getContext("2d");

    // ✅ HANCURKAN CHART LAMA
    if (customerRetentionChart) {
      customerRetentionChart.destroy();
    }

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "rgba(54, 162, 235, 0.8)");
    gradient.addColorStop(1, "rgba(153, 102, 255, 0.6)");

    // ✅ SIMPAN INSTANCE CHART
    customerRetentionChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: res.customers,
        datasets: [
          {
            label: "Total Orders",
            data: res.orders,
            backgroundColor: gradient,
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
            borderRadius: 6,
            hoverBackgroundColor: "rgba(153, 102, 255, 0.9)",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 1500,
          easing: "easeOutBounce",
        },
        scales: {
          x: {
            ticks: { color: "#495057", font: { weight: "500" } },
            grid: { display: false },
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: "#495057",
              precision: 0,
              callback: (value) => value.toLocaleString("id-ID"),
            },
            grid: { color: "rgba(0, 0, 0, 0.05)" },
          },
        },
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: "rgba(0,0,0,0.8)",
            callbacks: {
              label: (ctx) => `Total Order: ${ctx.parsed.y}`,
            },
          },
        },
      },
    });
  }).fail((xhr) => console.error("Error AJAX:", xhr.responseText));
}

function loadPaymentStatusChart() {
  $.getJSON("models/mdl_payment_status.php", function (res) {
    if (!res.success) {
      console.error("Gagal ambil data payment status:", res.message);
      return;
    }

    $("#payment-year").text(res.year);

    const ctx = document.getElementById("chPaymentStatus").getContext("2d");

    const colors = [
      "rgba(40, 167, 69, 0.8)", // Lunas - hijau
      "rgba(255, 193, 7, 0.8)", // Sebagian - kuning
      "rgba(220, 53, 69, 0.8)", // Belum Bayar - merah
    ];

    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: res.labels,
        datasets: [
          {
            data: res.values,
            backgroundColor: colors,
            borderColor: "rgba(255,255,255,0.9)",
            borderWidth: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%", // biar tampil donut
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              boxWidth: 15,
              font: { weight: "600" },
              color: "#495057",
            },
          },
          tooltip: {
            backgroundColor: "rgba(0,0,0,0.8)",
            callbacks: {
              label: (ctx) => `${ctx.label}: ${ctx.parsed} invoice`,
            },
          },
        },
        animation: {
          animateRotate: true,
          duration: 1500,
          easing: "easeOutBounce",
        },
      },
    });
  }).fail((xhr) => console.error("Error AJAX:", xhr.responseText));
}

//end card dashboard

function getTransaksi() {
  trxToday();
  todayExpense();
  expenseslastMonth();
  trxLastday();
  trxBatalToday();
  trxBatallastmonth();
  trxLastmonth();
  trxCurmonth();
  loadChart("monthly");
}
function expenseslastMonth() {
  $.ajax({
    type: "GET",
    url: "models/mdl_getexpLastMonth.php",
    dataType: "json",
    success: function (response) {
      var formater = Intl.NumberFormat();
      var hasil = formater.format(response.expenses);
      console.log("respon=", response);
      $("#lastmonth_expenses").html(hasil);
    },
  });
}
function trxToday() {
  $.ajax({
    type: "GET",
    url: "models/mdl_getTrxToday.php",
    dataType: "json",
    success: function (response) {
      var formater = Intl.NumberFormat();
      var hasil = formater.format(response.jml_trx);
      console.log("respon=", response);
      $("#todaytrx,#todaycust").html(hasil);
    },
  });
}

function todayExpense() {
  $.ajax({
    type: "GET",
    url: "models/mdl_getExpenseToday.php",
    dataType: "json",
    success: function (response) {
      var formater = Intl.NumberFormat();
      var hasil = formater.format(response.expenses);
      console.log("respon=", response);
      $("#today_expenses").html(hasil);
    },
  });
}

function loadcbItem($select, selectedId = null) {
  return $.getJSON("models/mdl_getitems.php", function (res) {
    $select.empty().append('<option value="">-- Pilih Item --</option>');

    if (res && res.data) {
      $.each(res.data, function (i, item) {
        $select.append(
          '<option value="' + item.id + '">' + item.nama_item + "</option>",
        );
      });
    }

    // set value selectedId setelah semua option ada
    if (selectedId) {
      $select.val(selectedId).trigger("change"); // trigger change untuk select2
    }

    // inisialisasi select2 jika belum diinisialisasi
    if (!$select.hasClass("select2-hidden-accessible")) {
      $select.select2({ dropdownParent: $("#modal"), width: "100%" });
    }
  });
}
$(document).ready(function () {
  // jaminan event tetap aktif, meskipun element di-reload
  $(document)
    .off("change", "#invoice_number")
    .on("change", "#invoice_number", function () {
      let id = $(this).val();
      console.log("Change event tertembak, id:", id);
      $("#invoice_id").val(id); // isi hidden input supaya ke kirim ke BE

      if (!id) {
        $("#invoiceInfoBox").hide();
        return;
      }

      $.getJSON("models/mdl_getinvoiceinfo.php", { id: id }, function (res) {
        console.log("Response dari BE:", res);
        if (res.success) {
          $("#infoTotal").text(
            "Rp " + Number(res.data.total).toLocaleString("id-ID"),
          );
          $("#infoPaid").text(
            "Rp " + Number(res.data.paid).toLocaleString("id-ID"),
          );
          $("#infoOutstanding").text(
            "Rp " + Number(res.data.outstanding).toLocaleString("id-ID"),
          );
          $("#invoiceInfoBox").show();
        } else {
          alert(res.message);
          $("#invoiceInfoBox").hide();
        }
      });
    });
});

function loadcbjo() {
  $.getJSON("models/mdl_getjoborder.php", function (return_data) {
    $("#jo_number,#job_order_id")
      .empty()
      .append('<option value="">-- Pilih Job Order --</option>');
    $.each(return_data.data, function (key, value) {
      $("#jo_number,#job_order_id").append(
        '<option value="' + value.id + '">' + value.jo_number + "</option>",
      );
    });
    $("#jo_number,#job_order_id").val("").trigger("change");
  });
}
//load jo untuk expense operasional
function loadJobOrderList() {
  $.getJSON("models/mdl_getjoborder_open.php", function (res) {
    const $sel = $("#job_order_id");
    $sel.empty().append('<option value="">Pilih Job Order</option>');

    if (res.success && res.data.length > 0) {
      res.data.forEach((opt) => {
        $sel.append(`<option value="${opt.id}">${opt.text}</option>`);
      });
    }
    $sel.trigger("change");
  });
}
//end load jo untuk operasioanl

function loadcbinvoice() {
  $.getJSON("models/mdl_getinvoicenumber.php", function (return_data) {
    $("#invoice_number")
      .empty()
      .append('<option value="">-- Pilih Invoice --</option>');
    $.each(return_data.data, function (key, value) {
      $("#invoice_number").append(
        '<option value="' +
          value.id +
          '">' +
          value.invoice_number +
          "</option>",
      );
    });
    $("#invoice_number").val("").trigger("change");
  });
}
function loadcbrekcust() {
  $.getJSON("models/mdl_getrekcust.php", function (return_data) {
    $("#rekening_cust")
      .empty()
      .append('<option value="">-- Pilih Rekening --</option>');
    $.each(return_data.data, function (key, value) {
      $("#rekening_cust").append(
        '<option value="' +
          value.id +
          '">' +
          " [" +
          value.nama_cust +
          "] " +
          value.bank_name +
          -value.account_number +
          "</option>",
      );
    });
    $("#rekening_cust").val("").trigger("change");
  });
}

function loadcbCustomer() {
  $.getJSON("models/mdl_getcustomer.php", function (return_data) {
    $("#customer,#customer_id")
      .empty()
      .append('<option value="0">-- Pilih Customer --</option>');
    $.each(return_data.data, function (key, value) {
      $("#customer,#customer_id").append(
        '<option value="' + value.id + '">' + value.nama_cust + "</option>",
      );
    });
    $("#customer,#customer_id").val("").trigger("change");
  });
}
function loadcbVendor() {
  $.getJSON("models/mdl_getvendor.php", function (return_data) {
    $("#vendor").empty().append('<option value="">-- Pilih Vendor --</option>');
    $.each(return_data.data, function (key, value) {
      $("#vendor").append(
        '<option value="' + value.id + '">' + value.nama_vendor + "</option>",
      );
    });
    $("#vendor").val("").trigger("change");
  });
}
function loadcbInvestor() {
  $.getJSON("models/mdl_getinvestor.php", function (return_data) {
    $("#investor").empty().append('<option value="">Thoriq (100%)</option>');
    $.each(return_data.data, function (key, value) {
      $("#investor").append(
        '<option value="' + value.id + '">' + value.fullname + "</option>",
      );
    });
    $("#customer").val("").trigger("change");
  });
}

function loadcbPengguna() {
  $("#nama_pengguna").append("<option selected>Pilih Pengguna</option>");
  $.getJSON("models/mdl_getPengguna.php", function (return_data) {
    $.each(return_data.data, function (key, value) {
      $("#nama_pengguna").append(
        '<option value="' + value[1] + '">' + value[2] + "</option>",
      );
    });
    $("#nama_pengguna").val("").trigger("change");
  });
}

function doCallBack(halaman) {
  switch (halaman) {
    case "view_mastertoko":
      console.log("Inisialisasi DataTable untuk halaman master toko ...");
      $("#datatable1").DataTable({
        scrollX: false, // kasih horizontal scroll
        responsive: true,
        columnDefs: [
          { responsivePriority: 1, targets: 0 }, // kolom pertama (NO) selalu tampil
          { responsivePriority: 2, targets: 1 }, // kolom nama diprioritaskan
        ],
        language: {
          searchPlaceholder: "Search...",
          sSearch: "",
          lengthMenu:
            '<span class="mr-2">Show</span> _MENU_ <span class="ml-2">items/page</span>',
        },
        ajax: {
          url: BASE_URL + "models/mdl_gettoko.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          {
            data: "no", // <-- nomor urut
            width: "5%",
          },
          {
            data: "id",
            visible: false,
            searchable: false,
          },
          {
            data: "name",
            width: "20%",
            searchable: true,
          },
          { data: "address" },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              return `<div align="center">
                            <button type="button" class="btn btn-oblong btn-sm btn-primary"
                                data-toggle="modal" data-target="#modal"
                                id="ubah" style="margin:2px"
                                onclick="getID(${data})">
                                <span class="fa fa-pencil"></span> Ubah
                            </button>
                            <button type="button" class="btn btn-oblong btn-sm btn-danger"
                                id="hapus" style="margin:2px"
                                onclick="hapusData(${data},'${full.name}')">
                                <span class="fa fa-trash-o"></span> Hapus
                            </button>
                        </div>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
    case "view_mastergudang":
      console.log("Inisialisasi DataTable untuk halaman master gudang ...");
      $("#datatable1").DataTable({
        scrollX: false, // kasih horizontal scroll
        responsive: true,
        columnDefs: [
          { responsivePriority: 1, targets: 0 }, // kolom pertama (NO) selalu tampil
          { responsivePriority: 2, targets: 1 }, // kolom nama diprioritaskan
        ],
        language: {
          searchPlaceholder: "Search...",
          sSearch: "",
          lengthMenu:
            '<span class="mr-2">Show</span> _MENU_ <span class="ml-2">items/page</span>',
        },
        ajax: {
          url: BASE_URL + "models/mdl_getgudang.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          {
            data: "no", // <-- nomor urut
            width: "5%",
          },
          {
            data: "id",
            visible: false,
            searchable: false,
          },
          {
            data: "name",
            width: "20%",
            searchable: true,
          },
          { data: "address" },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              return `<div align="center">
                            <button type="button" class="btn btn-oblong btn-sm btn-primary"
                                data-toggle="modal" data-target="#modal"
                                id="ubah" style="margin:2px"
                                onclick="getID(${data})">
                                <span class="fa fa-pencil"></span> Ubah
                            </button>
                            <button type="button" class="btn btn-oblong btn-sm btn-danger"
                                id="hapus" style="margin:2px"
                                onclick="hapusData(${data},'${full.name}')">
                                <span class="fa fa-trash-o"></span> Hapus
                            </button>
                        </div>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
    case "view_masterproduk":
      console.log("Inisialisasi DataTable untuk halaman master produk ...");
      $("#datatable1").DataTable({
        scrollX: false, // kasih horizontal scroll
        responsive: true,
        columnDefs: [
          { responsivePriority: 1, targets: 0 }, // kolom pertama (NO) selalu tampil
          { responsivePriority: 2, targets: 1 }, // kolom nama diprioritaskan
        ],
        language: {
          searchPlaceholder: "Search...",
          sSearch: "",
          lengthMenu:
            '<span class="mr-2">Show</span> _MENU_ <span class="ml-2">items/page</span>',
        },
        ajax: {
          url: BASE_URL + "models/mdl_getproduk.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          {
            data: "no", // <-- nomor urut
            width: "5%",
          },
          {
            data: "id",
            visible: false,
            searchable: false,
          },
          {
            data: "sku",
            width: "10%",
            searchable: true,
          },
          { data: "barcode" },
          { data: "name" },
          { data: "price_retail" },
          { data: "price_wholesale" },
          { data: "min_wholesale_qty" },
          { data: "cost_price" },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              return `<div align="center">
                            <button type="button" class="btn btn-oblong btn-sm btn-primary"
                                data-toggle="modal" data-target="#modal"
                                id="ubah" style="margin:2px"
                                onclick="getID(${data})">
                                <span class="fa fa-pencil"></span> Ubah
                            </button>
                            <button type="button" class="btn btn-oblong btn-sm btn-danger"
                                id="hapus" style="margin:2px"
                                onclick="hapusData(${data},'${full.name}')">
                                <span class="fa fa-trash-o"></span> Hapus
                            </button>
                        </div>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
    case "view_mastermember":
      console.log("Inisialisasi DataTable untuk halaman master member ...");
      $("#datatable1").DataTable({
        scrollX: false, // kasih horizontal scroll
        responsive: true,
        columnDefs: [
          { responsivePriority: 1, targets: 0 }, // kolom pertama (NO) selalu tampil
          { responsivePriority: 2, targets: 1 }, // kolom nama diprioritaskan
        ],
        language: {
          searchPlaceholder: "Search...",
          sSearch: "",
          lengthMenu:
            '<span class="mr-2">Show</span> _MENU_ <span class="ml-2">items/page</span>',
        },
        ajax: {
          url: BASE_URL + "models/mdl_getmember.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          {
            data: "no", // <-- nomor urut
            width: "5%",
          },
          {
            data: "id",
            visible: false,
            searchable: false,
          },
          {
            data: "name"
          },
          {
            data: "phone"
          },
          { data: "discount_percent" },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              return `<div align="center">
                            <button type="button" class="btn btn-oblong btn-sm btn-primary"
                                data-toggle="modal" data-target="#modal"
                                id="ubah" style="margin:2px"
                                onclick="getID(${data})">
                                <span class="fa fa-pencil"></span> Ubah
                            </button>
                            <button type="button" class="btn btn-oblong btn-sm btn-danger"
                                id="hapus" style="margin:2px"
                                onclick="hapusData(${data},'${full.name}')">
                                <span class="fa fa-trash-o"></span> Hapus
                            </button>
                        </div>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
  }
}

function printInvoice(id) {
  if (!id) return;
  const url = `views/print_invoice.php?id=${id}`;

  // buka di tab baru
  const win = window.open(url, "_blank");

  // auto-trigger print setelah halaman terbuka
  win.onload = function () {
    win.print();
  };
}

//----------------------------------------------------------------------------Blok DML database-----------------------------------------------------------//

function insertFormdata(formId) {
  // ===================================================
  // 🔥 BARU BUAT FORMDATA
  // ===================================================
  const myForm = $("#" + formId)[0];
  const models = $("#" + formId).attr("data-models");
  const mydata = new FormData(myForm);

  // ============================
  // VALIDASI
  // ============================
  let isValid = true;
  if (!isValid) return;

  // ============================
  // 🚀 LOADING
  // ============================
  let btn = $("#simpanData");
  let btnHtmlDefault = btn.html();

  btn.prop("disabled", true);
  btn.html(`<i class="fa fa-spinner fa-spin"></i> Saving...`);

  $.ajax({
    url: BASE_URL + "models/" + models + ".php",
    method: "POST",
    contentType: false,
    processData: false,
    dataType: "json",
    data: mydata,

    success: function (data) {
      btn.prop("disabled", false);
      btn.html(btnHtmlDefault);

      if (data.success) {
        new Messi(data.message, {
          title: "Pemberitahuan",
          titleClass: "anim success",
          modal: true,
          buttons: [{ id: 0, label: "Close", val: "X" }],
        });

        $(".data-table").DataTable().ajax.reload();
        $("#" + formId)
          .closest(".modal")
          .modal("hide");
      } else {
        new Messi(data.message, {
          titleClass: "anim warning",
          modal: true,
        });
      }
    },

    error: function (xhr) {
      btn.prop("disabled", false);
      btn.html(btnHtmlDefault);
      alert("ERROR: " + xhr.responseText);
    },
  });
}

function getID(id = null) {
  var modelsGet = $("#frm").attr("data-getid"); // file ambil data by id
  if (!id) return;

  $.ajax({
    url: BASE_URL + "models/" + modelsGet + ".php",
    type: "get",
    data: { id: id },
    dataType: "json",
    success: function (response) {
      // console.log("DEBUG getID() response:", response);
      // console.log("modelsGet:", modelsGet);
      if (!response || response.success === false) {
        var msg =
          response && response.message ? response.message : "Gagal ambil data.";
        alert(msg);
        return;
      }
      // isi field header
      $("[data-json]").each(function (_index, element) {
        var el = $(element);
        var key = el.data("json");
        if (el.hasClass("select2")) {
          el.val(response[key]).trigger("change");
        } else {
          el.val(response[key]);
        }
      });

      $("#modal").modal("show");
    },
    error: function (xhr, status, err) {
      console.error("getID error:", status, err, xhr.responseText);
      alert("Request gagal: " + (xhr.responseText || status));
    },
  });
}

function hapusData(ID, nama) {
  //console.log(">>> hapusData id:", id, "nama:", nama);
  const models = $("#frm").attr("data-hapus");
  new Messi("Hapus Data " + nama + "?", {
    title: "Konfirmasi Penghapusan Record",
    titleClass: "anim warning",
    modal: true,
    buttons: [
      { id: 0, label: "Yes", val: ID },
      { id: 1, label: "No", val: null },
    ],
    callback: function (val) {
      if (val) {
        $.ajax({
          url: BASE_URL + "models/" + models + ".php",
          type: "POST",
          data: { ID: val },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              new Messi(response.message, {
                title: "Pemberitahuan",
                titleClass: "anim success",
                buttons: [{ id: 0, label: "Close", val: "X" }],
              });
              $(".dataTable").DataTable().ajax.reload();
            } else {
              new Messi(response.message, {
                title: "Gagal",
                tirtleClass: "anim error",
                buttons: [{ id: 0, label: "Close", val: "X" }],
              });
            }
          },
          error: function () {
            new Messi("Terjadi kesalahan saat menghapus data.", {
              title: "Error",
              titleClass: "anim error",
              buttons: [{ id: 0, label: "Close", val: "X" }],
            });
          },
        });
      }
    },
  });
}
function updateCetak(id) {
  var models = $("#frm").attr("data-updateCetak");
  $.ajax({
    url: "models/" + models + ".php",
    type: "post",
    data: "id=" + id,
    dataType: "html",
    success: function (_response) {
      $(".dataTable").DataTable().ajax.reload();
    },
  });
}
function updateAproval(id) {
  var models = $("#frm").attr("data-Aproval");
  $.ajax({
    url: "models/" + models + ".php",
    type: "post",
    data: "id=" + id,
    dataType: "html",
    success: function (_response) {
      $(".dataTable").DataTable().ajax.reload();
    },
  });
}
function updateReject(id) {
  var models = $("#frm").attr("data-Reject");
  $.ajax({
    url: "models/" + models + ".php",
    type: "post",
    data: "id=" + id,
    dataType: "html",
    success: function (_response) {
      $(".dataTable").DataTable().ajax.reload();
    },
  });
}
function pembatalan(id) {
  var models = $("#frm").attr("data-batal");
  $.ajax({
    url: "models/" + models + ".php",
    type: "post",
    data: "id=" + id,
    dataType: "html",
    success: function (_response) {
      $(".dataTable").DataTable().ajax.reload();
    },
  });
}

//start report handling
// end sales invoice report

//end report handling

//-------------------------------------------------------------------------End Blok DML database-----------------------------------------------------------//
