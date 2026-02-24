<?php
// Ambil nama file dari URL, tanpa ekstensi
$pageFile = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ".php"));

// Mapping konfigurasi page header
$pageConfig = [
    "admin" => [
        "icon"       => "fa fa-chart-line",
        "title"      => "Dashboard",
        "subtitle"   => "Dashboard Performance Company",
        "breadcrumb" => ["Dashboard"]
    ],
    "view_mastertoko" => [
        "icon"       => "fa fa-address-card",
        "title"      => "Master Toko",
        "subtitle"   => "Masukan Data Toko",
        "breadcrumb" => ["Master", "Toko"]
    ],
    "view_mastergudang" => [
        "icon"       => "fa fa-truck",
        "title"      => "Master Gudang",
        "subtitle"   => "Masukan Data Gudang",
        "breadcrumb" => ["Master", "Gudang"]
    ],
    "view_masteritems" => [
        "icon"       => "fa fa-cube",
        "title"      => "Master Item",
        "subtitle"   => "Masukan Data Item",
        "breadcrumb" => ["Master", "Item"]
    ],
    "view_masterbank" => [
        "icon"       => "fa fa-credit-card",
        "title"      => "Master Rekening Bank",
        "subtitle"   => "Masukan Data Rekening Bank",
        "breadcrumb" => ["Master", "Rekening Bank"]
    ],
    "view_mastercoa" => [
        "icon"       => "fa fa-bookmark",
        "title"      => "Master Chart of Account",
        "subtitle"   => "Masukan Data Chart of Account",
        "breadcrumb" => ["Master", "Chart of Account"]
    ],
    "view_joborder" => [
        "icon"       => "fa fa-clipboard",
        "title"      => "Job Order",
        "subtitle"   => "Masukan Data Job Order",
        "breadcrumb" => ["Job Order", "Job Oder"]
    ],
    "view_paymentreq" => [
        "icon"       => "fa fa-university",
        "title"      => "Payment Request",
        "subtitle"   => "Buat Payment Request",
        "breadcrumb" => ["Payment Request", "Payment Request"]
    ],
    "view_aproval"   => [
        "icon"       => "fa fa-check-square",
        "title"      => "Approval",
        "subtitle"   => "Approval Payment Request",
        "breadcrumb" => ["Payment Request", "Approval"]
    ],
    "view_paymentreqpayment"   => [
        "icon"       => "fa fa-list-alt",
        "title"      => "List Approval",
        "subtitle"   => "Approval Payment History",
        "breadcrumb" => ["Payment Request", "Daftar Approval"]
    ],
    "view_invoice"   => [
        "icon"       => "fa fa-file-invoice",
        "title"      => "List Invoice",
        "subtitle"   => "Invoice",
        "breadcrumb" => ["Invoice", "Daftar Invoice"]
    ],
    "view_customerpayment"   => [
        "icon"       => "fa fa-wallet",
        "title"      => "List Customer Payment",
        "subtitle"   => "Payments",
        "breadcrumb" => ["Invoice", "Customer Payments"]
    ],
    "view_profitsharing"   => [
        "icon"       => "fa fa-balance-scale",
        "title"      => "List Profit Sharing",
        "subtitle"   => "Payments",
        "breadcrumb" => ["Profit Sharing", "List Profit Sharing"]
    ],
    "view_joexpense"   => [
        "icon"       => "fa fa-money-bill-wave",
        "title"      => "List Job Order Expense",
        "subtitle"   => "Expense",
        "breadcrumb" => ["Job Order", "Job Order Expense"]
    ],
    "view_profitsharing"   => [
        "icon"       => "fa fa-percent",
        "title"      => "List Profit Sharing",
        "subtitle"   => "Profit Sharing",
        "breadcrumb" => ["Profit Sharing", "List Profit Sharing"]
    ],
    "rpt_sales_invoice"   => [
        "icon"       => "fa fa-clipboard-list",
        "title"      => "List Sales Invoice",
        "subtitle"   => "Sales Invoice",
        "breadcrumb" => ["Laporan Sales Invoice", "List Sales Invoice"]
    ],
    "rpt_profitshare"   => [
        "icon"       => "fa fa-clipboard-list",
        "title"      => "Laporan Profit Share",
        "subtitle"   => "Laporan Profit Share",
        "breadcrumb" => ["Laporan Profit Share", "Profit Share"]
    ],
    "rpt_paymentcust"   => [
        "icon"       => "fa fa-clipboard-list",
        "title"      => "Laporan Pembayaran Customer",
        "subtitle"   => "Laporan Pembayaran Customer",
        "breadcrumb" => ["Laporan Pembayaran Customer", "List Pembayaran Customer"]
    ],
    "rpt_labarugi"   => [
        "icon"       => "fa fa-clipboard-list",
        "title"      => "Laporan Laba Rugi",
        "subtitle"   => "Laporan Laba Rugi",
        "breadcrumb" => ["Laporan Laba Rugi", "List Laba Rugi"]
    ],
    "rpt_labarugi_per_jo"   => [
        "icon"       => "fa fa-clipboard-list",
        "title"      => "Laporan Laba Rugi Per JO",
        "subtitle"   => "Laporan Laba Rugi Per JO",
        "breadcrumb" => ["Laporan Laba Rugi Per JO", "List Laba Rugi Per JO"]
    ],
    "view_rekening_cust"   => [
        "icon"       => "fa fa-credit-card",
        "title"      => "Rekening Customer",
        "subtitle"   => "Rekening Customer",
        "breadcrumb" => ["Master Rekening Customer", "Rekening Customer"]
    ],
    "view_rekening_vendor"   => [
        "icon"       => "fa fa-credit-card",
        "title"      => "Rekening Vendor",
        "subtitle"   => "Rekening Vendor",
        "breadcrumb" => ["Master Rekening Vendor", "Rekening Vendor"]
    ],
    "view_mastermarketing"   => [
        "icon"       => "fa fa-envelope-open",
        "title"      => "Master Marketing",
        "subtitle"   => "Master Marketing",
        "breadcrumb" => ["Master Marketing", "Master Marketing"]
    ],
    // tambahkan halaman lain di sini...
];

// Ambil config, kalau tidak ada pakai default
$page = $pageConfig[$pageFile] ?? [
    "icon"       => "fa fa-file",
    "title"      => "Halaman Tidak Dikonfigurasi",
    "subtitle"   => "Silakan tambahkan ke config",
    "breadcrumb" => ["Dashboard", "Unknown"]
];
