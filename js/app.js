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

//fungsi reset modal barang masuk
function resetBarangMasukModal() {
  let modal = $("#modal");

  // 1️⃣ Reset seluruh form
  modal.find("form")[0].reset();

  // 2️⃣ Kosongkan hidden id
  modal.find("input[name='id']").val("");

  // 3️⃣ Reset select2 header
  modal.find("select").val("").trigger("change");

  // 4️⃣ Kosongkan table item
  $("#tblBarangMasuk tbody").empty();

  // 5️⃣ Reset total
  $("#bm_total_display").val("");
  $("#bm_total").val("");
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

function loadcbToko() {
  $.getJSON(BASE_URL + "models/mdl_gettoko.php", function (return_data) {
    $("store_id")
      .empty()
      .append('<option value="0">-- Semua Store (Admin) --</option>');
    $.each(return_data.data, function (key, value) {
      $("#store_id").append(
        '<option value="' + value.id + '">' + value.name + "</option>",
      );
    });
    $("#store_id").val("").trigger("change");
  });
}

function loadcbSupplier() {
  $.getJSON(BASE_URL + "models/mdl_getsuppliers.php", function (return_data) {
    $("supplier_id")
      .empty()
      .append('<option value="0">-- Semua Supplier --</option>');
    $.each(return_data.data, function (key, value) {
      $("#supplier_id").append(
        '<option value="' + value.id + '">' + value.name + "</option>",
      );
    });
    $("#supplier_id").val("").trigger("change");
  });
}

function loadcbGudang() {
  $.getJSON(BASE_URL + "models/mdl_getgudang.php", function (return_data) {
    $("warehouse_id")
      .empty()
      .append('<option value="0">-- Semua Gudang --</option>');
    $.each(return_data.data, function (key, value) {
      $("#warehouse_id").append(
        '<option value="' + value.id + '">' + value.name + "</option>",
      );
    });
    $("#warehouse_id").val("").trigger("change");
  });
}

function loadProducts(selectElement, selectedId = null) {
  $.get(
    BASE_URL + "models/mdl_getproduk.php",
    function (res) {
      selectElement.empty();
      selectElement.append('<option value="">-- Pilih Produk --</option>');

      res.data.forEach(function (row) {
        selectElement.append(`
                <option value="${row.id}">
                    ${row.sku} - ${row.name}
                </option>
            `);
      });

      // 🔥 Set value setelah option ada
      if (selectedId) {
        selectElement.val(selectedId).trigger("change");
      }
    },
    "json",
  );
}
function loadLocation(type, selectElement) {
  let url = type === "warehouse" ? "mdl_getgudang.php" : "mdl_gettoko.php";

  $.get(
    BASE_URL + "models/" + url,
    function (res) {
      selectElement.empty();
      selectElement.append('<option value="">-- Pilih --</option>');

      res.data.forEach(function (row) {
        selectElement.append(`
        <option value="${row.id}">
          ${row.name}
        </option>
      `);
      });
    },
    "json",
  );
}
function loadReturnableItems(purchase_id) {
  // 🔥 parameter purchase_id untuk ambil item yang bisa direturn
  $.ajax({
    url: BASE_URL + "models/mdl_get_returnable_items.php",
    type: "GET",
    data: { purchase_id: purchase_id },
    dataType: "json",
    success: function (res) {
      let tbody = $("#tblReturnItems tbody");
      tbody.empty();

      res.data.forEach((row, i) => {
        tbody.append(`
          <tr>
            <td>${row.name}</td>
            <td>${row.purchased_qty}</td>
            <td>${row.returned_qty}</td>
            <td>${row.remaining_qty}</td>
            <td>
              <input type="number"
                     class="form-control return-qty"
                     data-id="${row.purchase_item_id}"
                     max="${row.remaining_qty}"
                     min="0">
            </td>
          </tr>
        `);
      });
    },
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
          {
            data: "price_retail",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "price_wholesale",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          { data: "min_wholesale_qty" },
          {
            data: "last_cost",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "avg_cost",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
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
            data: "name",
          },
          {
            data: "phone",
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
    case "view_mastersuppliers":
      console.log("Inisialisasi DataTable untuk halaman master supplier ...");
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
          url: BASE_URL + "models/mdl_getsuppliers.php",
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
          },
          {
            data: "phone",
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
    case "view_masteruser":
      console.log("Inisialisasi DataTable untuk halaman master user ...");
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
          url: BASE_URL + "models/mdl_getuser.php",
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
          },
          {
            data: "email",
          },
          { data: "role" },
          { data: "store_id" },
          { data: "is_active" },
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
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      loadcbToko(); // load toko untuk dropdown filter di halaman user

      break;
    case "view_barangmasuk":
      console.log("Inisialisasi DataTable untuk halaman pembelian ...");
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
          url: BASE_URL + "models/mdl_getbarangmasuk.php",
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
            data: "supplier_name",
          },
          {
            data: "warehouse_name",
          },
          {
            data: "total",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          { data: "invoice_no" },
          {
            data: "return_status",
            render: function (data) {
              if (data === "FULL RETURN") {
                return `<span class="badge badge-danger">FULL RETURN</span>`;
              }
              if (data === "PARTIAL RETURN") {
                return `<span class="badge badge-warning">PARTIAL</span>`;
              }
              return `<span class="badge badge-success">OPEN</span>`;
            },
          },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              let btnReturn = `
                <button type="button" class="btn btn-oblong btn-sm btn-warning"
                    style="margin:2px"
                    onclick="openReturnModal(${data})">
                    <span class="fa fa-undo"></span> Retur
                </button>`;

              if (full.return_status === "FULL RETURN") {
                btnReturn = `
                  <button type="button" class="btn btn-oblong btn-sm btn-secondary"
                      style="margin:2px"
                      disabled>
                      <span class="fa fa-ban"></span> Full Returned
                  </button>`;
              }

              return `<div align="center">
                  <button type="button" class="btn btn-oblong btn-sm btn-primary"
                      data-toggle="modal"
                      data-target="#modal"
                      style="margin:2px"
                      onclick="getID(${data})">
                      <span class="fa fa-pencil"></span> Ubah
                  </button>
                  ${btnReturn}
              </div>`;
            },
          },
        ],
      });

      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      loadcbSupplier(); // load supplier untuk dropdown filter di halaman pembelian
      loadcbGudang(); // load gudang untuk dropdown filter di halaman pembelian
      break;
    case "view_stok":
      console.log("Inisialisasi DataTable untuk halaman stok ...");
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
          url: BASE_URL + "models/mdl_getstok.php",
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
          },
          {
            data: "product_name",
          },
          {
            data: "location_type",
          },
          {
            data: "location_name",
          },
          {
            data: "stock_status",
          },
          { data: "qty" },
          { data: "updated_at" },
        ],
      });

      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
    case "view_transfer":
      console.log("Inisialisasi DataTable untuk halaman transfer ...");
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
          url: BASE_URL + "models/mdl_gettransfer.php",
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
            data: "from_name",
          },
          {
            data: "to_name",
          },
          {
            data: "total_item",
          },
          {
            data: "total_qty",
          },
          { data: "created_at" },
          {
            data: "id",
            width: "10%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              return `<div align="center">
                            <button type="button" class="btn btn-oblong btn-sm btn-primary"
                                data-toggle="modal" data-target="#modalDetailTransfer"
                                id="ubah" style="margin:2px"
                                onclick="detailTransfer(${data})">
                                <span class="fa fa-pencil"></span> Detail
                            </button>
                        </div>`;
            },
          },
        ],
      });

      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      loadcbSupplier(); // load supplier untuk dropdown filter di halaman pembelian
      loadcbGudang(); // load gudang untuk dropdown filter di halaman pembelian
      break;
    case "view_adjustment":
      console.log("Inisialisasi DataTable untuk halaman adjustment ...");
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
          url: BASE_URL + "models/mdl_getadjustment.php",
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
            data: "location",
          },
          {
            data: "product",
          },
          {
            data: "type",
            render: function (data) {
              if (data === "Tambah") {
                return '<span class="badge badge-success">Tambah</span>';
              }
              return '<span class="badge badge-danger">Kurang</span>';
            },
          },
          {
            data: "qty",
          },
          {
            data: "created_at",
          },
          {
            data: "note",
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      loadcbSupplier(); // load supplier untuk dropdown filter di halaman pembelian
      loadcbGudang(); // load gudang untuk dropdown filter di halaman pembelian
      break;
    case "view_riwayat":
      console.log("Inisialisasi DataTable untuk halaman riwayat ...");
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
          url: BASE_URL + "models/mdl_getmovement.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          {
            data: "no", // <-- nomor urut
            width: "5%",
          },
          {
            data: "date",
          },
          {
            data: "product",
          },
          {
            data: "from",
          },
          {
            data: "to",
          },
          {
            data: "qty",
          },
          {
            data: "type",
          },
          {
            data: "user",
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      loadcbSupplier(); // load supplier untuk dropdown filter di halaman pembelian
      loadcbGudang(); // load gudang untuk dropdown filter di halaman pembelian
      break;
    case "view_penjualan":
      console.log("Inisialisasi DataTable untuk halaman riwayat ...");
      $("select").select2({
        //dropdownParent: $("#modal"),
        width: "100%",
      });
      break;
    case "view_purchase_return":
      console.log("Inisialisasi DataTable untuk halaman retur pembelian ...");
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
          url: BASE_URL + "models/mdl_getpurchase_return.php",
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
            data: "purchase_id",
          },
          {
            data: "supplier_name", // dari maseter supplier
          },
          { data: "warehouse_name" }, // dari master warehouse
          { data: "return_date" },
          { data: "total" },
          { data: "note" },
          { data: "created_by" },
          { data: "created_at" },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              //console.log(data, type, full);
              let btnReturn = `
                  <button class="btn btn-warning btn-sm"
                    onclick="openSaleReturnModal(${data})">
                    Retur
                  </button>`;

              if (full.return_status === "FULL RETURN") {
                btnReturn = `
                  <button class="btn btn-secondary btn-sm" disabled>
                    Full Returned
                  </button>`;
              }
            },
          },
        ],
      });

      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      break;
    case "view_historysales":
      console.log("Inisialisasi DataTable untuk halaman pembelian ...");
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
          url: BASE_URL + "models/mdl_getsales.php",
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
            data: "invoice_no",
          },
          {
            data: "created_at",
          },
          { data: "store_name" },
          { data: "cashier_name" },
          {
            data: "total",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "discount",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "grand_total",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          { data: "payment_method" },
          {
            data: "return_status",
            render: function (data) {
              if (data === "FULL RETURN") {
                return `<span class="badge badge-danger">FULL</span>`;
              }
              if (data === "PARTIAL RETURN") {
                return `<span class="badge badge-warning">PARTIAL</span>`;
              }
              return `<span class="badge badge-success">OPEN</span>`;
            },
          },
          {
            data: "id",
            width: "20%",
            render: function (data, type, full) {
              return `<div align="center">
          <button class="btn btn-sm btn-info" onclick="openSaleReturnModal(${data})">
            Retur
          </button>
        </div>`;
            },
          },
        ],
      });

      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      $("#modal select").select2({
        dropdownParent: $("#modal"),
        width: "100%",
      });
      loadcbSupplier(); // load supplier untuk dropdown filter di halaman pembelian
      loadcbGudang(); // load gudang untuk dropdown filter di halaman pembelian
      break;
    case "view_profit":
      $("#datatable_profit").DataTable({
        ajax: {
          url: BASE_URL + "models/mdl_getProfit.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          { data: "no" },
          { data: "invoice_no" },
          { data: "date" },
          { data: "store" },
          { data: "cashier" },
          {
            data: "revenue",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "cogs",
            className: "text-right",
            render: (d) => formatRupiah(d),
          },
          {
            data: "profit",
            className: "text-right",
            render: function (d) {
              let color = d >= 0 ? "green" : "red";
              return `<span style="color:${color};font-weight:bold">${formatRupiah(d)}</span>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: Infinity,
      });
      break;
    case "view_profit_produk":
      $("#datatable_profit_product").DataTable({
        ajax: {
          url: BASE_URL + "models/mdl_profit_produk.php",
          type: "GET",
          dataSrc: "data",
        },
        columns: [
          { data: "no" },
          { data: "product" },
          { data: "qty" },
          { data: "revenue", className: "text-right", render: (d) => formatRupiah(d) },
          { data: "cogs", className: "text-right", render: (d) => formatRupiah(d) },
          {
            data: "profit",
            className: "text-right",
            render: function (d) {
              let color = d >= 0 ? "green" : "red";
              return `<b style="color:${color}">${formatRupiah(d)}</b>`;
            },
          },
          {
            data: "margin",
            className: "text-right",
            render: function (d) {
              let color = d >= 0 ? "blue" : "red";
              return `<b style="color:${color}">${d.toFixed(2)}%</b>`;
            },
          },
        ],
      });
      $(".dataTables_length select").select2({
        minimumResultsForSearch: 0,
      });
      break;
  }
}

//----------------------------------------------------------------------------Blok Profit-----------------------------------------------------------//
function reloadProfit() {
  let start = $("#start").val();
  let end = $("#end").val();

  let url =
    BASE_URL + "models/mdl_getProfit.php?start=" + start + "&end=" + end;

  $("#datatable_profit").DataTable().ajax.url(url).load();
}
function filterProfitProduct() {

  let start = $("#start_date").val();
  let end   = $("#end_date").val();

  if (!start || !end) {
    alert("Pilih tanggal dulu");
    return;
  }

  let url = BASE_URL + "models/mdl_profit_produk.php?start=" + start + "&end=" + end;

  $("#datatable_profit_product").DataTable()
    .ajax.url(url)
    .load();
}

function resetFilter() {

  $("#start_date").val("");
  $("#end_date").val("");

  $("#datatable_profit_product").DataTable()
    .ajax.url(BASE_URL + "models/mdl_profit_produk.php")
    .load();
}

//----------------------------------------------------------------------------Blok Penjualan-----------------------------------------------------------//
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

//----------------------------------------------------------------------------Blok Barang Masuk-----------------------------------------------------------//
function addBarangMasukRow() {
  let index = $("#tblBarangMasuk tbody tr").length;

  let row = `
        <tr>
            <td>
                <select name="items[${index}][product_id]" 
                        class="form-control product-select" required>
                    <option value="">-- Pilih Produk --</option>
                </select>
            </td>

            <td>
                <input type="number"
                       name="items[${index}][qty]"
                       class="form-control qty"
                       min="1" value="1">
            </td>

            <td>
                <input type="number"
                       name="items[${index}][cost_price]"
                       class="form-control cost"
                       min="0" value="0">
            </td>

            <td>
                <input type="text"
                       class="form-control subtotal text-right"
                       readonly>
            </td>

            <td width="5%" align="center">
                <button type="button"
                        class="btn btn-danger btn-sm remove-row">
                    Hapus
                </button>
            </td>
        </tr>
    `;

  $("#tblBarangMasuk tbody").append(row);
  let newRow = $("#tblBarangMasuk tbody tr:last .product-select");
  loadProducts(newRow);
  newRow.select2({
    dropdownParent: $("#modal"),
    width: "100%",
  });
}

//----------------------------------------------------------------------------Blok Transfer-----------------------------------------------------------//

function lockFromLocation(lock = true) {
  let fromType = $('[name="from_type"]');
  let fromId = $('[name="from_id"]');

  if (lock) {
    fromType.next(".select2").css({
      "pointer-events": "none",
      opacity: "0.7",
    });

    fromId.next(".select2").css({
      "pointer-events": "none",
      opacity: "0.7",
    });
  } else {
    fromType.next(".select2").css({
      "pointer-events": "",
      opacity: "",
    });

    fromId.next(".select2").css({
      "pointer-events": "",
      opacity: "",
    });
  }
}

$(document).on("click", ".remove-row", function () {
  //unlock lokasi asal saat baris transfer dihapus, jika tidak ada baris tersisa
  $(this).closest("tr").remove();

  let remaining = $("#tblTransfer tbody tr").length;

  if (remaining === 0) {
    lockFromLocation(false);
  }
});

function addTransferRow() {
  let fromType = $('[name="from_type"]').val();
  let fromId = $('[name="from_id"]').val();

  if (!fromType || !fromId) {
    alert("Pilih lokasi asal dulu.");
    return;
  }

  let index = $("#tblTransfer tbody tr").length;

  let row = `
    <tr>
      <td>
        <select name="items[${index}][product_id]"
                class="form-control product-select"
                required>
          <option value="">Loading...</option>
        </select>
      </td>
      <td>
        <input type="number"
               name="items[${index}][qty]"
               class="form-control"
               min="1"
               value="1"
               required>
      </td>
      <td align="center">
        <button type="button"
                class="btn btn-danger btn-sm remove-row">
          Hapus
        </button>
      </td>
    </tr>
  `;

  $("#tblTransfer tbody").append(row);

  // 🔥 LOCK lokasi setelah item pertama
  lockFromLocation(true);

  let select = $("#tblTransfer tbody tr:last .product-select");

  $.get(
    BASE_URL + "models/mdl_getstokbylokasi.php",
    {
      type: fromType,
      id: fromId,
    },
    function (res) {
      select.empty();
      select.append('<option value="">-- Pilih Produk --</option>');

      res.data.forEach(function (row) {
        select.append(`
            <option value="${row.product_id}">
              ${row.sku} - ${row.name} (Stok: ${row.qty})
            </option>
          `);
      });
    },
    "json",
  );

  select.select2({
    dropdownParent: select.closest(".modal"),
    width: "100%",
  });
}

$(document).on("change", '[name="from_type"]', function () {
  loadLocation($(this).val(), $('[name="from_id"]'));
});

$(document).on("change", '[name="to_type"]', function () {
  loadLocation($(this).val(), $('[name="to_id"]'));
});

function resetTransferModal() {
  let modal = $("#modal");

  modal.find("form")[0].reset();
  modal.find("select").val("").trigger("change");
  $("#tblTransfer tbody").empty();
}

$(document).on("change", '[name="from_type"], [name="from_id"]', function () {
  let type = $('[name="from_type"]').val();
  let id = $('[name="from_id"]').val();

  if (type && id) {
    $("#btnAddTransfer").prop("disabled", false);
  }
});

// fungsi untuk menampilkan detail transfer saat tombol detail diklik
function detailTransfer(id) {
  $.get(
    BASE_URL + "models/mdl_gettransferdetail.php",
    { id: id },
    function (res) {
      if (!res.success) {
        alert(res.message);
        return;
      }

      let tbody = $("#detailTransferBody");
      tbody.empty();

      res.data.forEach(function (row) {
        tbody.append(`
        <tr>
          <td>${row.sku}</td>
          <td>${row.name}</td>
          <td>${row.qty}</td>
        </tr>
      `);
      });

      $("#modalDetailTransfer").modal("show");
    },
    "json",
  );
}

//----------------------------------------------------------------------------Blok Barang Masuk & Transfer (shared)-----------------------------------------------------------//

// reindex nama input saat baris dihapus atau ditambah agar tetap berurutan dan tidak ada index yang terlewat
function reindexBarangMasuk() {
  $("#tblBarangMasuk tbody tr").each(function (i) {
    $(this).find(".product-select").attr("name", `items[${i}][product_id]`);

    $(this).find(".qty").attr("name", `items[${i}][qty]`);

    $(this).find(".cost").attr("name", `items[${i}][cost_price]`);
  });
}

$(document).on("click", ".remove-row", function () {
  $(this).closest("tr").remove();
  reindexBarangMasuk();
  updateBarangMasukTotal();
});

// update subtotal saat qty atau cost berubah
$(document).on("input", ".qty, .cost", function () {
  let row = $(this).closest("tr");

  let qty = parseFloat(row.find(".qty").val()) || 0;
  let cost = parseFloat(row.find(".cost").val()) || 0;

  let subtotal = qty * cost;

  row.find(".subtotal").val(subtotal.toLocaleString());

  updateBarangMasukTotal();
});

// update total saat baris dihapus atau saat qty/cost berubah
function updateBarangMasukTotal() {
  let total = 0;

  $("#tblBarangMasuk tbody tr").each(function () {
    let qty = parseFloat($(this).find(".qty").val()) || 0;
    let cost = parseFloat($(this).find(".cost").val()) || 0;

    total += qty * cost;
  });

  $("#bm_total_display").val(total.toLocaleString("id-ID"));
  $("#bm_total").val(total);
}

// hapus baris saat tombol hapus diklik
$(document).on("click", ".remove-row", function () {
  $(this).closest("tr").remove();
  updateBarangMasukTotal();
});

//----------------------------------------------------------------------------Blok Adjustment UI select (berdasarkan location_type)-----------------------------------------------------------//

$(document).on("change", '[name="location_type"]', function () {
  let type = $(this).val();
  let locationSelect = $('[name="location_id"]');
  let productSelect = $('[name="product_id"]');

  // reset dulu
  locationSelect.empty().append('<option value="">Loading...</option>');
  productSelect.empty().append('<option value="">-- Pilih Produk --</option>');

  if (!type) {
    locationSelect
      .empty()
      .append('<option value="">-- Pilih Lokasi --</option>');
    return;
  }

  let url = type === "warehouse" ? "mdl_getgudang.php" : "mdl_gettoko.php";

  $.get(
    BASE_URL + "models/" + url,
    function (res) {
      locationSelect.empty();
      locationSelect.append('<option value="">-- Pilih Lokasi --</option>');

      res.data.forEach(function (row) {
        locationSelect.append(`
        <option value="${row.id}">
          ${row.name}
        </option>
      `);
      });

      // refresh select2
      locationSelect.trigger("change.select2");
    },
    "json",
  );
});

//----------------------------------------------------------------------------Blok Adjustment UI select (berdasarkan stock)-----------------------------------------------------------//

$(document).on("change", '[name="location_id"]', function () {
  let type = $('[name="location_type"]').val();
  let locId = $(this).val();
  let productSelect = $('[name="product_id"]');

  productSelect.empty().append('<option value="">Loading...</option>');

  if (!type || !locId) {
    productSelect
      .empty()
      .append('<option value="">-- Pilih Produk --</option>');
    return;
  }

  $.get(
    BASE_URL + "models/mdl_getstokbylokasi.php",
    {
      type: type,
      id: locId,
    },
    function (res) {
      productSelect.empty();
      productSelect.append('<option value="">-- Pilih Produk --</option>');

      res.data.forEach(function (row) {
        productSelect.append(`
        <option value="${row.product_id}">
          ${row.sku} - ${row.name} (Stok: ${row.qty})
        </option>
      `);
      });

      productSelect.trigger("change.select2");
    },
    "json",
  );
});

//----------------------------------------------------------------------------Blok Pos Function-----------------------------------------------------------//
function loadProductPicker() {
  $.get(
    BASE_URL + "models/mdl_getproduklist.php",
    function (res) {
      let html = "";

      res.data.forEach((p) => {
        html += `
<div class="col-md-4">

<div class="product-card"
onclick='selectProduct(${JSON.stringify(p)})'>

<div class="product-sku">${p.sku}</div>

<div class="product-name">${p.name}</div>

<div class="product-price">
Rp ${formatRupiah(p.price_retail)}
</div>

</div>

</div>
`;
      });

      $("#productGrid").html(html);
    },
    "json",
  );
}

$(document).on("shown.bs.modal", "#modalProduk", function () {
  loadProductPicker();
});

function selectProduct(product) {
  // fungsi untuk menambahkan produk ke cart saat dipilih dari modal
  addToCart(product);

  $("#modalProduk").modal("hide");

  $("#scan_barcode").focus();
}

function formatRupiah(angka) {
  //fungsi untuk format angka ke rupiah, misal 10000 jadi 10.000
  let number_string = angka.replace(/[^,\d]/g, "").toString();
  let split = number_string.split(",");
  let sisa = split[0].length % 3;
  let rupiah = split[0].substr(0, sisa);
  let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    let separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  return rupiah;
}
$(document).on("input", "#sale_pay", function () {
  let raw = $(this).val().replace(/\D/g, "");

  if (raw === "") {
    $(this).val("");
    $("#sale_change").val(0);
    return;
  }

  let formatted = formatRupiah(raw);

  $(this).val(formatted);

  calculateChange();
});

$("#scan_barcode").autocomplete({
  // aktifkan autocomplete pada input barcode dengan jQuery UI Autocomplete
  minLength: 1,

  source: function (request, response) {
    $.get(
      BASE_URL + "models/mdl_searchproduct.php",
      { term: request.term },
      function (data) {
        response(data);
      },
      "json",
    );
  },

  select: function (event, ui) {
    addToCart(ui.item.product);

    $("#scan_barcode").val("");

    return false;
  },
});

$(document).on("keydown", "#scan_barcode", function (e) {
  if (e.key !== "Enter") return;

  e.preventDefault();

  let barcode = $(this).val().trim();

  console.log("SCAN:", barcode);

  if (!barcode) return;

  $.get(
    BASE_URL + "models/mdl_getproductbarcode.php",
    { barcode: barcode },
    function (res) {
      console.log(res);

      if (!res.success) {
        alert("Produk tidak ditemukan");
        return;
      }

      addToCart(res.data);
    },
    "json",
  );

  $(this).val("");
});

//fungsi untuk menambahkan produk ke cart saat barcode discan atau saat dipilih dari dropdown, jika produk sudah ada di cart maka qty ditambah 1, jika belum ada maka produk ditambahkan ke cart dengan qty 1
let saleCart = [];

function addToCart(product) {
  // modifikasi untuk support penjualan secara grosir dengan cek min_wholesale_qty dan price_wholesale
  let index = saleCart.findIndex((p) => p.product_id == product.id);

  if (index !== -1) {
    saleCart[index].qty++;
  } else {
    saleCart.push({
      product_id: product.id,
      name: product.name,
      price_retail: product.price_retail,
      price_wholesale: product.price_wholesale,
      min_wholesale_qty: product.min_wholesale_qty,
      price: product.price_retail,
      qty: 1,
      is_wholesale: false,
    });
    console.log("Added to cart:", product);
  }

  applyPricing(index !== -1 ? index : saleCart.length - 1);
  renderCart();
}

// fungsi render table cart berdasarkan data di saleCart, menghitung subtotal dan total, serta menampilkan di UI
function applyPricing(i) {
  let item = saleCart[i];

  if (item.min_wholesale_qty > 0 && item.qty >= item.min_wholesale_qty) {
    item.price = item.price_wholesale;
    item.is_wholesale = true;
  } else {
    item.price = item.price_retail;
    item.is_wholesale = false;
  }
}

function renderCart() {
  let tbody = $("#tblSale tbody");
  tbody.empty();

  let subtotal = 0;

  saleCart.forEach((item, i) => {
    let sub = item.price * item.qty;
    subtotal += sub;

    tbody.append(`
      <tr>
        <td>${i + 1}</td>
        <td>
        ${item.name}
        ${item.is_wholesale ? '<span class="badge badge-success ml-1">GROSIR</span>' : ""}
      </td>
        <td>${formatRupiah(item.price)}</td>
        <td>
          <input type="number"
                 class="form-control qty-input"
                 data-index="${i}"
                 value="${item.qty}"
                 min="1">
        </td>
        <td style="${item.is_wholesale ? "color:green;font-weight:bold" : ""}">
          ${formatRupiah(item.price)}
        </td>
        <td>
          <button class="btn btn-danger btn-sm"
                  onclick="removeItem(${i})">
          x
          </button>
        </td>
      </tr>
    `);
  });

  $("#sale_subtotal").text(formatRupiah(subtotal));

  calculateTotal();
}

// fungsi update qty di cart saat input qty diubah, dengan validasi minimal 1, lalu render ulang cart
$(document).on("change", ".qty-input", function () {
  let i = $(this).data("index");
  let qty = parseInt($(this).val());

  if (qty < 1) qty = 1;

  saleCart[i].qty = qty;
  applyPricing(i);
  renderCart();
});

// fungsi untuk menghapus item dari cart berdasarkan index, lalu render ulang cart
function removeItem(i) {
  saleCart.splice(i, 1);
  renderCart();
}

// fungsi untuk menghitung total akhir setelah diskon, dan menampilkan di UI
function calculateTotal() {
  let subtotal = 0;

  saleCart.forEach((item) => {
    subtotal += item.price * item.qty;
  });

  let discount = parseFloat($("#sale_discount").val()) || 0;

  let grand = subtotal - discount;

  $("#sale_grandtotal").text(formatRupiah(grand));

  calculateChange();
}

//fungsi hitung kembalian saat input pembayaran diubah, dengan validasi minimal 0, lalu tampilkan di UI
$("#sale_pay").on("keyup", calculateChange);

function calculateChange() {
  let subtotal = 0;

  saleCart.forEach((item) => {
    subtotal += item.price * item.qty;
  });

  let discount = parseFloat($("#sale_discount").val()) || 0;

  let grand = subtotal - discount;

  let pay = $("#sale_pay").val().replace(/\./g, "");

  pay = parseFloat(pay) || 0;

  let change = pay - grand;

  if (change < 0) change = 0;

  $("#sale_change").val(formatRupiah(change));
}

// fungsi untuk menghitung ulang kembalian saat diskon diubah, karena total akhir akan berubah
$(document).on("keyup", "#sale_pay", function () {
  calculateChange();
});

$(document).on("keyup", "#sale_discount", function () {
  calculateTotal();
});

// fungsi untuk menyimpan transaksi penjualan, dengan payload berisi data item di cart, diskon, dan pembayaran, lalu kirim ke server dengan AJAX POST, jika berhasil maka cart dikosongkan dan UI direset
function saveSale() {
  if (saleCart.length === 0) {
    alert("Tidak ada item");
    return;
  }

  let payload = {
    items: saleCart,
    discount: $("#sale_discount").val(),
    pay: $("#sale_pay").val(),
    payment_method: $("#sale_payment_method").val(),
  };

  $.ajax({
    url: BASE_URL + "models/mdl_sale.php",
    type: "POST",
    data: JSON.stringify(payload),
    contentType: "application/json",
    dataType: "json",

    success: function (res) {
      if (res.success) {
        // 🧾 PRINT TANPA BUKA TAB
        $("#printFrame").attr(
          "src",
          BASE_URL + "views/sales/print_struk.php?id=" + res.sale_id,
        );

        saleCart = [];
        renderCart();

        $("#sale_pay").val("");
        $("#sale_discount").val(0);

        $("#scan_barcode").focus();
      } else {
        alert(res.message);
      }
    },
  });
}

// fungsi untuk format angka ke format rupiah, misal 10000 jadi 10.000
function formatRupiah(num) {
  return new Intl.NumberFormat("id-ID").format(num);
}

function saveReturn() {
  // fungsi untuk menyimpan retur pembelian, dengan payload berisi purchase_id dan array items yang diretur (purchase_item_id dan qty), lalu kirim ke server dengan AJAX POST, jika berhasil maka modal ditutup dan datatable direload
  let purchase_id = $("#purchase_id").val();

  let items = [];

  $(".return-qty").each(function () {
    let qty = parseFloat($(this).val()) || 0;

    if (qty > 0) {
      items.push({
        purchase_item_id: $(this).data("id"),
        qty: qty,
      });
    }
  });

  if (items.length === 0) {
    alert("Tidak ada item yang diretur");
    return;
  }

  $.ajax({
    url: BASE_URL + "models/mdl_save_purchase_return.php",
    type: "POST",
    data: JSON.stringify({
      purchase_id: purchase_id,
      items: items,
    }),
    contentType: "application/json",
    dataType: "json",
    success: function (res) {
      if (res.success) {
        alert("Retur berhasil");

        $("#modalReturn").modal("hide");

        $("#datatable1").DataTable().ajax.reload();
      } else {
        alert(res.message);
      }
    },
  });
}

function saveSaleReturn() {
  let sale_id = $("#sale_id").val();
  let items = [];

  $(".sale-return-qty").each(function () {
    let qty = parseFloat($(this).val()) || 0;

    if (qty > 0) {
      items.push({
        sale_item_id: $(this).data("sale_item_id"),
        qty: qty,
      });
    }
  });

  if (items.length === 0) {
    alert("Tidak ada item retur");
    return;
  }

  $.ajax({
    url: BASE_URL + "models/mdl_save_sale_return.php",
    type: "POST",
    data: JSON.stringify({ sale_id, items }),
    contentType: "application/json",
    dataType: "json",
    success: function (res) {
      if (res.success) {
        alert("Retur berhasil");
        $("#modalSaleReturn").modal("hide");
        $("#datatable1").DataTable().ajax.reload();
      } else {
        alert(res.message);
      }
    },
  });
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
      // 🔥 Khusus barang masuk
      if (response.items) {
        $("#tblBarangMasuk tbody").empty();

        response.items.forEach(function (item) {
          addBarangMasukRow();

          let row = $("#tblBarangMasuk tbody tr:last");
          let select = row.find(".product-select");

          loadProducts(select, item.product_id);

          row.find(".qty").val(item.qty);
          row.find(".cost").val(item.cost_price);

          let subtotal = item.qty * item.cost_price;
          row.find(".subtotal").val(subtotal.toLocaleString("id-ID"));
        });

        updateBarangMasukTotal();
      }

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

function openReturnModal(purchase_id) {
  console.log("OPEN RETURN MODAL:", purchase_id);

  $("#modalReturn").modal("show");

  $("#purchase_id").val(purchase_id);

  loadReturnableItems(purchase_id);
}

function openSaleReturnModal(sale_id) {
  $("#sale_id").val(sale_id);
  $("#modalSaleReturn").modal("show");

  $.ajax({
    url: BASE_URL + "models/mdl_get_returnable_sale_items.php",
    type: "GET",
    data: { sale_id: sale_id },
    dataType: "json",
    success: function (res) {
      let tbody = $("#tblSaleReturn tbody");
      tbody.empty();

      if (!res.success) {
        alert(res.message);
        return;
      }

      if (res.data.length === 0) {
        tbody.append(
          `<tr><td colspan="5" align="center">Semua item sudah diretur</td></tr>`,
        );
        return;
      }

      res.data.forEach((row) => {
        tbody.append(`
          <tr>
            <td>${row.name}</td>
            <td>${row.sold_qty}</td>
            <td>${row.returned_qty}</td>
            <td>${row.remaining_qty}</td>
            <td>
              <input type="number"
                     class="form-control sale-return-qty"
                     data-sale_item_id="${row.sale_item_id}"
                     max="${row.remaining_qty}"
                     min="0">
            </td>
          </tr>
        `);
      });
    },
  });
}
//start report handling
// end sales invoice report

//end report handling

//-------------------------------------------------------------------------End Blok DML database-----------------------------------------------------------//
