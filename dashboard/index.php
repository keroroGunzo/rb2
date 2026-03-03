<?php
require '../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/x-icon" href="/rb2/favicon.ico">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Twitter -->
  <meta name="twitter:site" content="@themepixels">
  <meta name="twitter:creator" content="@themepixels">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Tobaqon Antobaq Trans">
  <meta name="twitter:description" content="Moving Up.">


  <!-- Facebook -->
  <meta property="og:url" content="">
  <meta property="og:title" content="Rizky Berkah">
  <meta property="og:description" content="">


  <!-- Meta -->
  <meta name="description" content="Your Logistics Solution.">
  <meta name="author" content="ThemePixels">

  <title>Rizky Berkah</title>

  <!-- vendor css -->
  <link href="../lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="../lib/highlightjs/styles/github.css" rel="stylesheet">
  <link href="../lib/select2/css/select2.min.css" rel="stylesheet">
  <link href="../lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
  <link href="../lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="../lib/rickshaw/rickshaw.min.css" rel="stylesheet">
  <link href="../js/messi/messi.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">


  <!-- Bracket CSS -->
  <link rel="stylesheet" href="../css/bracket.css">
  <!--custom css -->
  <link href="../css/custom.css" rel="stylesheet">

</head>

<body>

  <!-- ########## START: LEFT PANEL ########## -->
  <div class="br-logo"><img src="../img/logoRB3.png" width="200" height="50"></div>
  <div class="br-sideleft sideleft-scrollbar">
    <label class="sidebar-label pd-x-10 mg-t-20 op-3">Navigation</label>
    <ul class="br-sideleft-menu">
      <li class="br-menu-item">
        <a href="index.php" class="br-menu-link dummy active">
          <i class="menu-item-icon faicon fa fa-home"></i>
          <span class="menu-item-label">Dashboard</span>
        </a><!-- br-menu-link -->
      </li><!-- br-menu-item -->
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-database"></i>
          <span class="menu-item-label">Master Data</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_mastertoko" class="sub-link">Toko</a></li>
          <li class="sub-item"><a href="view_mastergudang" class="sub-link">Gudang</a></li>
          <li class="sub-item"><a href="view_masterproduk" class="sub-link">Produk</a></li>
          <li class="sub-item"><a href="view_mastermember" class="sub-link">Member</a></li>
          <li class="sub-item"><a href="view_mastersuppliers" class="sub-link">Suplier</a></li>
          <li class="sub-item"><a href="view_masteruser" class="sub-link">User</a></li>
        </ul>
      </li>
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-briefcase"></i>
          <span class="menu-item-label">Pembelian</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_joborder" class="sub-link">Purchase Order</a></li>
          <li class="sub-item"><a href="view_barangmasuk" class="sub-link">Barang Masuk</a></li>
          <li class="sub-item"><a href="view_joexpense" class="sub-link">Retur Ke Supplier</a></li>
        </ul>
      </li><!-- br-menu-item -->
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-file-invoice-dollar"></i>
          <span class="menu-item-label">Inventory</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_stok" class="sub-link">Stok Saat Ini</a></li>
          <li class="sub-item"><a href="view_transfer" class="sub-link">Transfer Barang</a></li>
          <li class="sub-item"><a href="view_adjustment" class="sub-link">Penyesuaian Stok</a></li>
          <li class="sub-item"><a href="view_paymentreqpayment" class="sub-link">Riwayat Pergerakan</a></li>
        </ul>
      </li><!-- br-menu-item -->
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-file-invoice"></i>
          <span class="menu-item-label">Penjualan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_invoice" class="sub-link">Penjualan</a></li>
          <li class="sub-item"><a href="view_customerpayment" class="sub-link">Retur Penjualan</a></li>
        </ul>
      </li>
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-file-invoice"></i>
          <span class="menu-item-label">Laporan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_invoice" class="sub-link">Laporan Penjualan</a></li>
          <li class="sub-item"><a href="view_customerpayment" class="sub-link">Laporan Stok</a></li>
          <li class="sub-item"><a href="view_customerpayment" class="sub-link">Laba Rugi</a></li>
        </ul>
      </li>
      <li class="br-menu-item">
        <a href="#" class="br-menu-link with-sub dummy">
          <i class="menu-item-icon faicon fa fa-file-invoice"></i>
          <span class="menu-item-label">Pengaturan</span>
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub">
          <li class="sub-item"><a href="view_invoice" class="sub-link">Profil Toko</a></li>
          <li class="sub-item"><a href="view_customerpayment" class="sub-link">Logout</a></li>
        </ul>
      </li>

    </ul><!-- br-sideleft-menu -->
  </div><!-- br-sideleft -->
  <!-- ########## END: LEFT PANEL ########## -->
  <!-- ########## START: HEAD PANEL MAIL########## -->
  <div class="br-header">
    <div class="br-header-left">
      <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
      <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
      <div class="input-group hidden-xs-down wd-170 transition">
        <!-- <input id="searchbox" type="text" class="form-control" placeholder="Search"> -->
        <!-- <span class="input-group-btn">
          <button class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button>
        </span> -->
      </div>
    </div>
    <div class="br-header-right">
      <nav class="nav">
        <div class="dropdown">
          <a href="" class="nav-link pd-x-3 pos-relative" data-toggle="dropdown">
            <i class="fa fa-user tx-20"></i>
            <!-- start: if statement -->
            <!-- <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle"></span> -->
            <!-- end: if statement -->
          </a>
          <div class="dropdown-menu dropdown-menu-header">
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->

        <div class="dropdown">
          <a href="" class="nav-link nav-link-profile" data-toggle="dropdown" style="padding-top: 1px !important; margin-top: 1px !important">
            <span class="logged-name hidden-md-down"><?php echo $_SESSION['name'] ?></span>
            <img src="https://via.placeholder.com/500" class="wd-32 rounded-circle" alt="">
            <!-- <span class="square-10 bg-success"></span> -->
          </a>
          <div class="dropdown-menu dropdown-menu-header wd-250">
            <div class="tx-center">
              <a href=""><img src="https://via.placeholder.com/500" class="wd-80 rounded-circle" alt=""></a>
              <h6 class="logged-fullname"><?php echo $_SESSION['store_id'] ?></h6>
              <h6 class="logged-fullname"><?php echo $_SESSION['name'] ?></h6>
              <p><?php echo $_SESSION['role'] ?></p>
              <p><?php echo $_SESSION['store_id'] ?></p>
            </div>
            <!-- <hr>
            <div class="tx-center">
              <span class="profile-earning-label">Earnings After Taxes</span>
              <h3 class="profile-earning-amount">$13,230 <i class="icon ion-ios-arrow-thin-up tx-success"></i></h3>
              <span class="profile-earning-text">Based on list price.</span>
            </div>
            <hr> -->
            <ul class="list-unstyled user-profile-nav">
              <li><a href=""><i class="icon ion-ios-person"></i> Edit Profile</a></li>
              <li><a href=""><i class="icon ion-ios-gear"></i> Settings</a></li>
              <!-- <li><a href=""><i class="icon ion-ios-download"></i> Downloads</a></li>
              <li><a href=""><i class="icon ion-ios-star"></i> Favorites</a></li>
              <li><a href=""><i class="icon ion-ios-folder"></i> Collections</a></li> -->
              <li><a href="../auth/logout.php"><i class="icon ion-power"></i> Sign Out</a></li>
            </ul>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
      </nav>
    </div><!-- br-header-right -->
  </div><!-- br-header -->
  <!-- ########## END: HEAD PANEL ########## -->
  <!-- ########## START: MAIN PANEL ########## -->
  <div class="br-mainpanel">
    <!-- br-pagetitle -->
    <div class="br-pagetitle">
      <i class="fa fa-home" style="font-size:48px"></i>
      <div>
        <h4>Dashboard</h4>
        <p class="mg-b-0">Summary Dashboard Transaction</p>
      </div>
    </div><!-- br-pagetitle -->
    <div class="br-pagebody">
      <div class="row row-sm">
        <div class="col-sm-6 col-xl-3">
          <div class="bg-info rounded overflow-hidden">
            <div class="pd-x-20 pd-t-20 d-flex align-items-center">
              <i class="ion ion-briefcase tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">
                  Job Order Hari Ini
                </p>
                <p id="jo-today-count" class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1">0</p>
                <span class="tx-11 tx-roboto tx-white-8">Dibuat hari ini</span>
              </div>
            </div>
            <!-- Wave Chart Background -->
            <canvas id="joTodayWaveChart"></canvas>
          </div>
        </div><!-- col-3 -->
        <!-- TODAY SALES -->
        <div class="col-sm-6 col-xl-3">
          <div class="bg-purple rounded overflow-hidden">
            <div class="pd-x-20 pd-t-20 d-flex align-items-center">
              <i class="ion ion-bag tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">INVOICE PENJUALAN HARI INI</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1" id="sales-today">$329,291</p>
                <span class="tx-11 tx-roboto tx-white-8">Total Invoice hari ini</span>
              </div>
            </div>
            <canvas id="salesWaveChart"></canvas>
          </div>
        </div>

        <!-- EXPENSE HARI INI -->
        <div class="col-sm-6 col-xl-3">
          <div class="bg-danger rounded overflow-hidden">
            <div class="pd-x-20 pd-t-20 d-flex align-items-center">
              <i class="ion ion-cash tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">PENGELUARAN HARI INI</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1" id="expense-today">Rp 0</p>
                <span class="tx-11 tx-roboto tx-white-8">Total pengeluaran hari ini</span>
              </div>
            </div>
            <canvas id="expenseWaveChart"></canvas>
          </div>
        </div>
        <!-- COST HARI INI -->
        <div class="col-sm-6 col-xl-3">
          <div class="bg-warning rounded overflow-hidden">
            <div class="pd-x-20 pd-t-20 d-flex align-items-center">
              <i class="ion ion-pricetag tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">BIAYA JO HARI INI</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1" id="cost-today">Rp 0</p>
                <span class="tx-11 tx-roboto tx-white-8">Total harga beli Items JO hari ini</span>
              </div>
            </div>
            <canvas id="costWaveChart"></canvas>
          </div>
        </div>

        <!-- PROFIT HARI INI -->
        <div class="col-sm-6 col-xl-3">
          <div class="bg-success rounded overflow-hidden">
            <div class="pd-x-20 pd-t-20 d-flex align-items-center">
              <i class="ion ion-stats-bars tx-60 lh-0 tx-white op-7"></i>
              <div class="mg-l-20">
                <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase tx-white-8 mg-b-10">PROFIT HARI INI</p>
                <p class="tx-24 tx-white tx-lato tx-bold mg-b-0 lh-1" id="profit-today">Rp 0</p>
                <span class="tx-11 tx-roboto tx-white-8">Perkiraan keuntungan bersih</span>
              </div>
            </div>
            <canvas id="profitWaveChart"></canvas>
          </div>
        </div>
      </div><!-- brrow -->
      <div class="row row-sm mg-t-20 d-flex align-items-stretch">
        <div class="col-sm-6 d-flex">
          <div class="card bd-0 shadow-base flex-fill">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Invoice Penjualan</h6>
                <p>Per Bulan dalam 1 Tahun</p>
              </div>
              <div class="d-sm-flex">
                <div>
                  <p class="mg-b-5 tx-uppercase tx-10 tx-mont tx-semibold">Total Penjualan</p>
                  <h4 id="total-sales-year" class="tx-lato tx-inverse tx-bold mg-b-0">Rp 0</h4>
                  <span class="tx-12 tx-success tx-roboto">Tahun <span id="chart-year"></span></span>
                </div>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="ch5" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>

        <div class="col-sm-6 d-flex">
          <div class="card bd-0 shadow-base flex-fill">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Invoice Penjualan vs Pengeluaran</h6>
                <p>Perbandingan per Bulan dalam 1 Tahun</p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="chSalesExpense" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row row-sm mg-t-20 d-flex align-items-stretch">
        <div class="col-sm-6 mg-t-20">
          <div class="card bd-0 shadow-base">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Profit Margin</h6>
                <p>Perbandingan Sales, Expense, dan Profit per Bulan</p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="chProfitMargin" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-sm-6 mg-t-20">
          <div class="card bd-0 shadow-base">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Top 5 Customers</h6>
                <p>Pelanggan dengan Total Sales Tertinggi Tahun <span id="topCustomerYear"></span></p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="chTopCustomer" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row row-sm mg-t-20 d-flex align-items-stretch">
        <div class="col-sm-6">
          <div class="card bd-0 shadow-base">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Trend Tonase Penjualan</h6>
                <p>Total qty (tonase) per bulan dalam 1 tahun</p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="chTonaseMonthly" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card bd-0 shadow-base">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Customer Retention</h6>
                <p>Top 10 Customer dengan Transaksi Lebih dari 1 kali</p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-15 pd-b-25">
              <canvas id="chCustomerRetention" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row row-sm mg-t-20 d-flex align-items-stretch">
        <div class="col-sm-6 mg-t-20">
          <div class="card bd-0 shadow-base">
            <div class="d-md-flex justify-content-between pd-25">
              <div>
                <h6 class="tx-13 tx-uppercase tx-inverse tx-semibold tx-spacing-1">Payment Status Overview</h6>
                <p>Distribusi status pembayaran invoice tahun <span id="payment-year"></span></p>
              </div>
            </div>
            <div class="pd-l-25 pd-r-25 pd-b-25">
              <canvas id="chPaymentStatus" class="ht-250 ht-sm-300"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div style="height:50px;"></div> <!-- spacer -->
    </div><!-- pagebody -->
  </div><!-- main panel -->

  <!-- ########## END: MAIN PANEL ########## -->
  <script src="../lib/jquery/jquery.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <script src="../lib/jquery-ui/ui/widgets/datepicker.js"></script>
  <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="lib/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
  <script src="../lib/moment/min/moment.min.js"></script>
  <script src="../lib/peity/jquery.peity.min.js"></script>
  <script src="../lib/rickshaw/vendor/d3.min.js"></script>
  <script src="../lib/rickshaw/vendor/d3.layout.min.js"></script>
  <script src="../lib/rickshaw/rickshaw.min.js"></script>
  <script src="../lib/rickshaw/rickshaw.min.js"></script>
  <script src="../lib/highlightjs/highlight.pack.min.js"></script>
  <script src="../lib/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
  <script src="../lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
  <script src="../lib/select2/js/select2.min.js"></script>
  <script src="../js/bracket.js"></script>
  <script src="../js/app.js"></script>
  <script src="../js/messi/messi.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
  <script>
    const BASE_URL = "http://localhost/rb2/";

    // =============================
    // AUTO LOGOUT AFTER 15 MINUTES
    // =============================

    // durasi idle (milidetik)
    const IDLE_LIMIT = 15 * 60 * 1000; // 15 menit

    let idleTimer;

    // fungsi logout otomatis
    function autoLogout() {
      // arahkan ke file logout kamu
      window.location.href = "../auth/logout.php";
    }

    // reset timer setiap ada aktivitas
    function resetTimer() {
      clearTimeout(idleTimer);
      idleTimer = setTimeout(autoLogout, IDLE_LIMIT);
    }

    // event listener untuk aktivitas
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer;
    window.ontouchstart = resetTimer;
    window.onclick = resetTimer;
    window.onkeypress = resetTimer;
    window.addEventListener('scroll', resetTimer, true);
  </script>




</body>

</html>