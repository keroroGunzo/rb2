-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 15, 2025 at 06:38 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tobaqontrans`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int NOT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `account_holder` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `bank_name`, `account_number`, `account_holder`, `created_at`) VALUES
(1, 'BCA', '1234567890', 'PT Tobaqontrans', '2025-08-29 09:09:22'),
(3, 'BNI', '122545024', 'Thoriq Hidayat', '2025-09-02 05:20:58'),
(4, 'Danamon', '08987639', 'Tobaqontrans', '2025-09-02 05:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `code` varchar(20) NOT NULL,
  `nama_coa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` enum('asset','liability','equity','revenue','expense') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`code`, `nama_coa`, `type`) VALUES
('101', 'Kas', 'asset'),
('102', 'Piutang Usaha', 'asset'),
('201', 'Hutang Vendor', 'liability'),
('301', 'Modal Pemilik', 'equity'),
('401', 'Pendapatan Penjualan', 'revenue'),
('501', 'Beban Operasional', 'expense');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `nama_cust` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat_cust` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `npwp` varchar(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `nama_cust`, `alamat_cust`, `phone`, `email`, `npwp`, `created_at`) VALUES
(1, 'PT Sumber Rejeki', 'Jl. Sudirman No.1', '08123456789', 'sumber@customer.com', '', '2025-08-29 09:09:22'),
(2, 'CV Makmur Jayas', 'Jl. Diponegoro No.5', '08129876543', 'makmur@customer.com', '', '2025-08-29 09:09:22'),
(7, 'PT Sumber Rejeki Juara', 'Jl. Sudirman No.1', '08123456789', 'sumber@customer.com', '', '2025-09-02 01:27:23'),
(8, 'PT.BSALogistics Indonesia', 'JL.Gresik No. 1-5 Surabaya', '0313723727', 'info@bsa-logistics.com', '', '2025-09-02 01:28:28'),
(10, 'PT. Pbm Win Surabaya', 'JL. Tanjung Batu No.25-27 Surabaya', '0313570688', '-', '094.618.533.7-605.000', '2025-09-12 07:54:18'),
(12, 'percobaan', 'percobaan', '', 'asdasdas@sdafasd.com', '', '2025-09-22 09:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `customer_bank_accounts`
--

CREATE TABLE `customer_bank_accounts` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_bank_accounts`
--

INSERT INTO `customer_bank_accounts` (`id`, `customer_id`, `bank_name`, `account_number`, `account_name`, `is_default`, `created_at`) VALUES
(1, 1, 'MANDIRI', '125456221154', 'MANDIRI PT. SUMBER REJEKI', 0, '2025-09-24 07:43:23'),
(2, 2, 'BRI', '698458524', 'BRI CV MAKMUR JAYAS', 0, '2025-09-24 07:43:23'),
(3, 1, 'MANDIRI', '4587454', 'PT. Sumber Rejeki', 0, '2025-11-13 07:09:29'),
(4, 8, 'BRI', '5464715', 'BSA Logistics Indonesia', 0, '2025-11-13 08:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `description` varchar(70) NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `status` enum('open','paid','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tax_rate` decimal(6,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `pph_rate` decimal(10,2) DEFAULT '0.00',
  `pph_amount` decimal(18,2) DEFAULT '0.00',
  `updated_at` datetime DEFAULT NULL,
  `reimbuse` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `description`, `job_order_id`, `invoice_date`, `due_date`, `total_amount`, `status`, `created_at`, `tax_rate`, `tax_amount`, `pph_rate`, `pph_amount`, `updated_at`, `reimbuse`) VALUES
(1, 'INV/11/2025/6594', 'Tjiwi Kimia Export', 1, '2025-11-09', '2025-11-16', 1213200.00, 'paid', '2025-11-09 16:43:14', 1.10, 13200.00, 0.00, 0.00, NULL, 0),
(2, 'INV/11/2025/7019', 'Percobaan', 2, '2025-11-11', '2025-11-12', 1188936.00, 'paid', '2025-11-11 02:10:19', 1.10, 13200.00, 2.00, 24264.00, NULL, 0),
(3, 'INV/11/2025/2776', 'Percobaan', 3, '2025-11-11', '2025-11-12', 495390.00, 'paid', '2025-11-11 06:32:56', 1.10, 5500.00, 2.00, 10110.00, NULL, 0),
(9, 'INV/11/2025/3363', 'Handling Customs Tax', 4, '2025-11-12', '2025-11-19', 26660671.14, 'paid', '2025-11-12 10:29:23', 1.10, 295996.47, 2.00, 544095.33, NULL, 0),
(10, 'INV/11/2025/7914', 'QUARANTINE REIMBURSEMENT', 4, '2025-11-13', '2025-11-20', 8985000.00, 'paid', '2025-11-13 01:38:34', 0.00, 0.00, 0.00, 0.00, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice_id` int DEFAULT NULL,
  `job_order_item_id` int DEFAULT NULL,
  `qty` decimal(12,2) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `tax_rate` decimal(6,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) DEFAULT NULL,
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `job_order_item_id`, `qty`, `unit_price`, `tax_rate`, `tax_amount`, `total`, `grand_total`, `deskripsi`) VALUES
(1, 1, 1, 1.00, 1200000.00, 0.00, 0.00, 1200000.00, 1200000.00, 'Trucking 1 X 40\"'),
(2, 2, 2, 1.00, 1200000.00, 0.00, 0.00, 1200000.00, 1200000.00, ''),
(3, 3, 3, 1.00, 500000.00, 0.00, 0.00, 500000.00, 500000.00, 'Biaya Trucking'),
(11, 9, 4, 8969.59, 3000.00, 0.00, 0.00, 26908770.00, 26908770.00, ''),
(12, 10, 5, 1.00, 4885000.00, 0.00, 0.00, 4885000.00, 4885000.00, ''),
(13, 10, 6, 1.00, 4000000.00, 0.00, 0.00, 4000000.00, 4000000.00, ''),
(14, 10, 7, 1.00, 100000.00, 0.00, 0.00, 100000.00, 100000.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `nama_item` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text,
  `unit` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `nama_item`, `description`, `unit`, `created_at`) VALUES
(1, 'Trucking', 'Item Trucking', 'Doc', '2025-10-15 10:11:07'),
(2, 'Lolo', 'Lift On Lift Off', 'Doc', '2025-10-15 10:11:31'),
(3, 'HANDLING CUSTOMS', 'Biaya Handling Customs', 'Doc', '2025-11-06 08:35:55'),
(4, 'QUARANTINE REIMBURSEMENT', 'QUARANTINE REIMBURSEMENT', 'Doc', '2025-11-12 09:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

CREATE TABLE `job_orders` (
  `id` int NOT NULL,
  `jo_number` varchar(50) NOT NULL,
  `customer_id` int DEFAULT NULL,
  `investor_id` int DEFAULT NULL,
  `profit_type` enum('investor','tonase') NOT NULL DEFAULT 'investor',
  `tonase` decimal(12,2) DEFAULT NULL,
  `rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `status` enum('Open','In Progress','Closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Open',
  `vessel` varchar(20) NOT NULL,
  `polpod` varchar(50) NOT NULL,
  `eta` date NOT NULL,
  `etd` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) DEFAULT '0.00',
  `tax_rate` float DEFAULT '0',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_orders`
--

INSERT INTO `job_orders` (`id`, `jo_number`, `customer_id`, `investor_id`, `profit_type`, `tonase`, `rate`, `description`, `status`, `vessel`, `polpod`, `eta`, `etd`, `start_date`, `end_date`, `subtotal`, `tax_rate`, `tax_amount`, `total_amount`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'TBQN/11/2025/0001', 7, 4, 'investor', 0.00, 0.00, 'TJIWI KIMIA 1 X 40\"', 'Closed', 'USS. Arbumain', 'Tanjung Perak Hanoi', '2025-11-12', '2025-11-19', '2025-11-09', '2025-11-11', 1200000.00, 0, 0.00, 1200000.00, NULL, '2025-11-09 16:41:14', '2025-11-09 23:44:24'),
(2, 'TBQN/11/2025/0002', 1, 4, 'investor', 0.00, 0.00, 'Percobaan', 'In Progress', '-', 'Percobaan', '2025-11-11', '2025-11-11', '2025-11-11', '2025-11-13', 1200000.00, 0, 0.00, 1200000.00, NULL, '2025-11-11 01:48:01', '2025-11-11 11:11:28'),
(3, 'TBQN/11/2025/0003', 1, 3, 'investor', 0.00, 0.00, 'Percobaan', 'Closed', 'SV SURABAYA 301', 'TANJUNG PERAK', '2025-11-11', '2025-11-13', '2025-11-11', '2025-11-12', 500000.00, 0, 0.00, 500000.00, NULL, '2025-11-11 06:32:09', '2025-11-11 13:33:25'),
(4, 'TBQN/11/2025/0004', 10, 3, 'tonase', 8969.59, 3000.00, 'BRAZILIAN HIPRO SOYBEAN MEAL (FEED GRADE)', 'Closed', 'SV SURABAYA', 'SANTOS, BRAZIL', '2025-12-11', '2025-11-12', '2025-12-11', '2025-11-13', 35893770.00, 0, 0.00, 35893770.00, NULL, '2025-11-12 03:56:48', '2025-11-13 08:39:27'),
(5, 'TBQN/11/2025/0005', 1, 3, 'investor', 0.00, 0.00, 'GEAR MOTOR SEW', 'Open', '-', 'BALIKPAPAN', '2025-11-18', '2025-11-20', '2025-11-13', '2025-11-14', 1800000.00, 0, 0.00, 1800000.00, NULL, '2025-11-13 10:20:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_expenses`
--

CREATE TABLE `job_order_expenses` (
  `id` int NOT NULL,
  `job_order_id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `expense_type` varchar(50) NOT NULL DEFAULT 'lainnya',
  `description` text NOT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `expense_date` date NOT NULL DEFAULT (curdate()),
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_items`
--

CREATE TABLE `job_order_items` (
  `id` int NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `harga_beli` decimal(15,2) DEFAULT '0.00',
  `qty` decimal(12,2) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `tax_rate` decimal(6,1) DEFAULT '0.0',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `is_costable` tinyint(1) NOT NULL DEFAULT '0',
  `total` decimal(15,2) DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_order_items`
--

INSERT INTO `job_order_items` (`id`, `job_order_id`, `item_id`, `harga_beli`, `qty`, `unit_price`, `tax_rate`, `tax_amount`, `is_costable`, `total`, `locked`) VALUES
(1, 1, 1, 800000.00, 1.00, 1200000.00, 0.0, 0.00, 1, 1200000.00, 1),
(2, 2, 1, 0.00, 1.00, 1200000.00, 0.0, 0.00, 0, 1200000.00, 0),
(3, 3, 1, 0.00, 1.00, 500000.00, 0.0, 0.00, 0, 500000.00, 0),
(4, 4, 3, 0.00, 8969.59, 3000.00, 0.0, 0.00, 0, 26908770.00, 0),
(5, 4, 4, 0.00, 1.00, 4885000.00, 0.0, 0.00, 0, 4885000.00, 0),
(6, 4, 4, 0.00, 1.00, 4000000.00, 0.0, 0.00, 0, 4000000.00, 0),
(7, 4, 4, 0.00, 1.00, 100000.00, 0.0, 0.00, 0, 100000.00, 0),
(8, 5, 2, 500000.00, 1.00, 600000.00, 0.0, 0.00, 1, 600000.00, 1),
(9, 5, 1, 800000.00, 1.00, 1200000.00, 0.0, 0.00, 1, 1200000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_marketing`
--

CREATE TABLE `job_order_marketing` (
  `id` int NOT NULL,
  `job_order_id` int NOT NULL,
  `marketer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `share_percent` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_shares`
--

CREATE TABLE `job_order_shares` (
  `id` int NOT NULL,
  `job_order_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_order_shares`
--

INSERT INTO `job_order_shares` (`id`, `job_order_id`, `payment_id`, `user_id`, `amount`, `created_at`) VALUES
(1, 1, 1, 2, 1793918.00, '2025-11-09 16:19:12'),
(2, 1, 1, 2, 606600.00, '2025-11-09 16:44:24'),
(3, 1, 1, 4, 606600.00, '2025-11-09 16:44:24'),
(4, 2, 2, 2, 594468.00, '2025-11-11 04:11:28'),
(5, 2, 2, 4, 594468.00, '2025-11-11 04:11:28'),
(6, 3, 3, 3, 346773.00, '2025-11-11 06:33:25'),
(7, 3, 3, 2, 148617.00, '2025-11-11 06:33:25'),
(8, 4, 4, 2, 26908770.00, '2025-11-12 07:45:21'),
(9, 4, 5, 2, 26908770.00, '2025-11-12 08:17:18'),
(10, 4, 6, 2, 26908770.00, '2025-11-12 10:35:08'),
(11, 4, 7, 2, 26908770.00, '2025-11-13 01:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` int NOT NULL,
  `entry_date` date DEFAULT NULL,
  `description` text,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `entry_date`, `description`, `reference_type`, `reference_id`, `created_at`) VALUES
(1, '2025-08-10', 'Invoice ke Customer INV-001', 'invoice', 1, '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `id` int NOT NULL,
  `journal_entry_id` int DEFAULT NULL,
  `account_code` varchar(20) DEFAULT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `credit` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments_customer`
--

CREATE TABLE `payments_customer` (
  `id` int NOT NULL,
  `invoice_id` int DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `customer_account` varchar(100) DEFAULT NULL,
  `company_account` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments_customer`
--

INSERT INTO `payments_customer` (`id`, `invoice_id`, `payment_date`, `amount`, `method`, `reference_no`, `customer_account`, `company_account`, `created_at`, `description`) VALUES
(1, 1, '2025-11-09', 1213200.00, 'transfer', '5643', '2', '4', '2025-11-09 16:44:24', 'TJIWI KIMIA 1 X 40\"'),
(2, 2, '2025-11-11', 1188936.00, 'cash', '5643', '2', '4', '2025-11-11 04:11:28', 'Percobaan'),
(3, 3, '2025-11-11', 495390.00, 'transfer', '522522', '2', '4', '2025-11-11 06:33:25', 'Percobaan'),
(4, 4, '2025-11-12', 26660671.14, 'transfer', '123456', '564785241', '4', '2025-11-12 07:45:21', 'Pelunasan Handling Customs'),
(5, 8, '2025-11-12', 26660671.14, 'transfer', '56458', '1235422147', '4', '2025-11-12 08:17:18', 'Pelunasan Handling Customs'),
(6, 9, '2025-11-12', 26660671.14, 'transfer', '3234234', '32423423423', '4', '2025-11-12 10:35:08', 'Pelunasan Handling Customs'),
(7, 10, '2025-11-13', 8985000.00, 'transfer', '3234234', '65478', '4', '2025-11-13 01:39:27', 'QUARANTINE REIMBURSEMENT');

-- --------------------------------------------------------

--
-- Table structure for table `payment_request`
--

CREATE TABLE `payment_request` (
  `id` int NOT NULL,
  `request_number` varchar(50) NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `bank_account_id` int DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_by` varchar(50) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `approve_date` timestamp NULL DEFAULT NULL,
  `description` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_request`
--

INSERT INTO `payment_request` (`id`, `request_number`, `job_order_id`, `vendor_id`, `amount`, `bank_account_id`, `status`, `requested_by`, `approved_by`, `request_date`, `approve_date`, `description`, `updated_at`, `created_at`) VALUES
(1, 'PR/11/2025/0001', 1, NULL, 1200000.00, NULL, 'approved', NULL, 'thoriq', '2025-11-08 17:00:00', '2025-11-09 16:42:16', 'Bayar Trucking', '2025-11-09 23:42:16', '2025-11-09 23:41:44'),
(2, 'PR/11/2025/0002', 5, NULL, 1800000.00, NULL, 'approved', NULL, 'thoriq', '2025-11-12 17:00:00', '2025-11-13 10:25:17', 'GEAR MOTOR SEW', '2025-11-13 17:25:17', '2025-11-13 17:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `payment_request_items`
--

CREATE TABLE `payment_request_items` (
  `id` int NOT NULL,
  `payment_request_id` int NOT NULL,
  `job_order_item_id` int DEFAULT NULL,
  `qty` decimal(12,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `request_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_request_items`
--

INSERT INTO `payment_request_items` (`id`, `payment_request_id`, `job_order_item_id`, `qty`, `harga`, `tax_rate`, `tax_amount`, `total`, `request_amount`, `amount`) VALUES
(1, 1, 1, 1.00, 1200000.00, 0.00, 0.00, 1200000.00, 1200000.00, 1200000.00),
(2, 2, 8, 1.00, 600000.00, 0.00, 0.00, 600000.00, 600000.00, 600000.00),
(3, 2, 9, 1.00, 1200000.00, 0.00, 0.00, 1200000.00, 1200000.00, 1200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment_request_payment`
--

CREATE TABLE `payment_request_payment` (
  `id` int NOT NULL,
  `payment_request_id` int NOT NULL,
  `approved_by` int NOT NULL,
  `approved_at` datetime NOT NULL,
  `rek_asal` varchar(100) NOT NULL,
  `rek_tujuan` varchar(100) NOT NULL,
  `nominal_transfer` decimal(18,2) NOT NULL,
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_request_payment`
--

INSERT INTO `payment_request_payment` (`id`, `payment_request_id`, `approved_by`, `approved_at`, `rek_asal`, `rek_tujuan`, `nominal_transfer`, `catatan`, `created_at`) VALUES
(1, 1, 2, '2025-11-09 23:42:16', '4', '5', 1200000.00, 'OK', '2025-11-09 16:42:16'),
(2, 2, 2, '2025-11-13 17:25:17', '4', '1', 1800000.00, 'PERCOBAAN BAYAR VENDOR', '2025-11-13 10:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `profit_sharing`
--

CREATE TABLE `profit_sharing` (
  `id` int NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `investor` varchar(50) DEFAULT NULL,
  `capital_share` decimal(15,2) DEFAULT NULL,
  `profit_share` decimal(15,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `calculated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','marketing') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'admin',
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `fullname`, `email`, `created_at`) VALUES
(1, 'yudha', '$2y$10$5AIZ2izZ3pPqLQfWT1Z2H.2qFfKZZVQm9yT6QG1RQj4iFvtltNHty', 'admin', 'Yudha', 'yudha@tobaqontrans.com', '2025-08-30 10:55:01'),
(2, 'thoriq', '$2y$10$SSSIRPwbDFlu8wwD6WnQu.WeFUkHZ4CnLYP2mMYpdZBmAhlpVUB/C', 'owner', 'Thoriq Hidayat', 'thoriq@tobaqontrans.com', '2025-08-30 10:55:01'),
(3, 'imron', '$2y$10$wT3I1NzIT4SgxYpDhrzqLOd1iI7yqY9MijRZOr5yeRZ4u16JTYZFa', 'owner', 'Imron', 'imron@tobaqontrans.com', '2025-08-30 10:55:01'),
(4, 'marketing1', '$2y$10$7Dqy56tn5GHne.lcMSzOQ.v.Sp13Z1Jbo.oZ5N9WaLbsL4YNoEKQW', 'marketing', 'Alex', 'alex@tobaqontrans.com', '2025-09-28 11:35:03');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int NOT NULL,
  `nama_vendor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat_vendor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `nama_vendor`, `bank_name`, `account_number`, `alamat_vendor`, `phone`, `email`, `created_at`) VALUES
(5, 'PT. WIJAYA INDO TRANS', 'BCA', '170299278', 'JL.Bronggalan Sawah No.20', '082234423234', 'wit-info@wit.com', '2025-09-20 12:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bank_accounts`
--

CREATE TABLE `vendor_bank_accounts` (
  `id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendor_bank_accounts`
--

INSERT INTO `vendor_bank_accounts` (`id`, `vendor_id`, `bank_name`, `account_number`, `account_name`, `is_default`, `created_at`) VALUES
(1, 5, 'BCA', '170299278', 'PT. WIJAYA INDO TRANS', 0, '2025-11-13 06:15:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_bank_accounts`
--
ALTER TABLE `customer_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `job_order_id` (`job_order_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `job_order_item_id` (`job_order_item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jo_number` (`jo_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `fk_investor` (`investor_id`);

--
-- Indexes for table `job_order_expenses`
--
ALTER TABLE `job_order_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_joe_joborder` (`job_order_id`);

--
-- Indexes for table `job_order_items`
--
ALTER TABLE `job_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_id` (`job_order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `job_order_marketing`
--
ALTER TABLE `job_order_marketing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jom_jo` (`job_order_id`);

--
-- Indexes for table `job_order_shares`
--
ALTER TABLE `job_order_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jos_jo` (`job_order_id`),
  ADD KEY `fk_jos_payment` (`payment_id`),
  ADD KEY `fk_jos_user` (`user_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `account_code` (`account_code`);

--
-- Indexes for table `payments_customer`
--
ALTER TABLE `payments_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `payment_request`
--
ALTER TABLE `payment_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_request_number` (`request_number`),
  ADD KEY `job_order_id` (`job_order_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `bank_account_id` (`bank_account_id`);

--
-- Indexes for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_request_id` (`payment_request_id`),
  ADD KEY `job_order_item_id` (`job_order_item_id`);

--
-- Indexes for table `payment_request_payment`
--
ALTER TABLE `payment_request_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_request_id` (`payment_request_id`);

--
-- Indexes for table `profit_sharing`
--
ALTER TABLE `profit_sharing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_id` (`job_order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_bank_accounts`
--
ALTER TABLE `vendor_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_bank_accounts`
--
ALTER TABLE `customer_bank_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_orders`
--
ALTER TABLE `job_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_order_expenses`
--
ALTER TABLE `job_order_expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_items`
--
ALTER TABLE `job_order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `job_order_marketing`
--
ALTER TABLE `job_order_marketing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_shares`
--
ALTER TABLE `job_order_shares`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments_customer`
--
ALTER TABLE `payments_customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_request`
--
ALTER TABLE `payment_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_request_payment`
--
ALTER TABLE `payment_request_payment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profit_sharing`
--
ALTER TABLE `profit_sharing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vendor_bank_accounts`
--
ALTER TABLE `vendor_bank_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_bank_accounts`
--
ALTER TABLE `customer_bank_accounts`
  ADD CONSTRAINT `customer_bank_accounts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`job_order_item_id`) REFERENCES `job_order_items` (`id`);

--
-- Constraints for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD CONSTRAINT `fk_investor` FOREIGN KEY (`investor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `job_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `job_order_expenses`
--
ALTER TABLE `job_order_expenses`
  ADD CONSTRAINT `fk_joe_joborder` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_order_items`
--
ALTER TABLE `job_order_items`
  ADD CONSTRAINT `job_order_items_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `job_order_marketing`
--
ALTER TABLE `job_order_marketing`
  ADD CONSTRAINT `fk_jom_jo` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_order_shares`
--
ALTER TABLE `job_order_shares`
  ADD CONSTRAINT `fk_jos_jo` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jos_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments_customer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jos_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD CONSTRAINT `journal_lines_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  ADD CONSTRAINT `journal_lines_ibfk_2` FOREIGN KEY (`account_code`) REFERENCES `chart_of_accounts` (`code`);

--
-- Constraints for table `payments_customer`
--
ALTER TABLE `payments_customer`
  ADD CONSTRAINT `payments_customer_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`);

--
-- Constraints for table `payment_request`
--
ALTER TABLE `payment_request`
  ADD CONSTRAINT `payment_request_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `payment_request_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `payment_request_ibfk_3` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`);

--
-- Constraints for table `payment_request_items`
--
ALTER TABLE `payment_request_items`
  ADD CONSTRAINT `pri_fk1` FOREIGN KEY (`payment_request_id`) REFERENCES `payment_request` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pri_fk2` FOREIGN KEY (`job_order_item_id`) REFERENCES `job_order_items` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `payment_request_payment`
--
ALTER TABLE `payment_request_payment`
  ADD CONSTRAINT `payment_request_payment_ibfk_1` FOREIGN KEY (`payment_request_id`) REFERENCES `payment_request` (`id`);

--
-- Constraints for table `profit_sharing`
--
ALTER TABLE `profit_sharing`
  ADD CONSTRAINT `profit_sharing_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
