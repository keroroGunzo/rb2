-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 31, 2025 at 07:33 AM
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
(2, 'Mandiri', '9876543210', 'PT Tobaqontrans', '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `code` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` enum('asset','liability','equity','revenue','expense') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`code`, `name`, `type`) VALUES
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
  `name` varchar(100) NOT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `phone`, `email`, `created_at`) VALUES
(1, 'PT Sumber Rejeki', 'Jl. Sudirman No.1', '08123456789', 'sumber@customer.com', '2025-08-29 09:09:22'),
(2, 'CV Makmur Jaya', 'Jl. Diponegoro No.2', '08129876543', 'makmur@customer.com', '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `status` enum('open','paid') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `job_order_id`, `invoice_date`, `due_date`, `total_amount`, `status`, `created_at`) VALUES
(1, 'INV-001', 1, '2025-08-10', '2025-08-20', 81000000.00, 'open', '2025-08-29 09:09:22');

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
  `total` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `job_order_item_id`, `qty`, `unit_price`, `total`) VALUES
(1, 1, 1, 5000.00, 15000.00, 75000000.00),
(2, 1, 2, 3.00, 2000000.00, 6000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `unit` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `code`, `name`, `description`, `unit`, `created_at`) VALUES
(1, 'ITM001', 'Solar Industri', 'BBM untuk transportasi', 'liter', '2025-08-29 09:09:22'),
(2, 'ITM002', 'Jasa Angkut', 'Transportasi logistik', 'ritase', '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

CREATE TABLE `job_orders` (
  `id` int NOT NULL,
  `jo_number` varchar(50) NOT NULL,
  `customer_id` int DEFAULT NULL,
  `description` text,
  `status` enum('open','closed') DEFAULT 'open',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_orders`
--

INSERT INTO `job_orders` (`id`, `jo_number`, `customer_id`, `description`, `status`, `start_date`, `end_date`, `created_by`, `created_at`) VALUES
(1, 'JO-001', 1, 'Pengiriman BBM ke Jakarta', 'open', '2025-08-01', NULL, 'Yudha', '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `job_order_items`
--

CREATE TABLE `job_order_items` (
  `id` int NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `qty` decimal(12,2) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_order_items`
--

INSERT INTO `job_order_items` (`id`, `job_order_id`, `item_id`, `qty`, `unit_price`, `total`) VALUES
(1, 1, 1, 5000.00, 15000.00, 75000000.00),
(2, 1, 2, 3.00, 2000000.00, 6000000.00);

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

--
-- Dumping data for table `journal_lines`
--

INSERT INTO `journal_lines` (`id`, `journal_entry_id`, `account_code`, `debit`, `credit`) VALUES
(1, 1, '102', 81000000.00, 0.00),
(2, 1, '401', 0.00, 81000000.00);

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments_customer`
--

INSERT INTO `payments_customer` (`id`, `invoice_id`, `payment_date`, `amount`, `method`, `reference_no`, `created_at`) VALUES
(1, 1, '2025-08-15', 81000000.00, 'transfer', 'TRF12345', '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `payments_request`
--

CREATE TABLE `payments_request` (
  `id` int NOT NULL,
  `job_order_id` int DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `bank_account_id` int DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_by` varchar(50) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `approve_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments_request`
--

INSERT INTO `payments_request` (`id`, `job_order_id`, `vendor_id`, `amount`, `bank_account_id`, `status`, `requested_by`, `approved_by`, `request_date`, `approve_date`) VALUES
(1, 1, 1, 20000000.00, 1, 'approved', 'Yudha', NULL, '2025-08-29 09:09:22', NULL);

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

--
-- Dumping data for table `profit_sharing`
--

INSERT INTO `profit_sharing` (`id`, `job_order_id`, `investor`, `capital_share`, `profit_share`, `percentage`, `calculated_at`) VALUES
(1, 1, 'Thoriq', 60000000.00, 14700000.00, 70.00, '2025-08-29 09:09:22'),
(2, 1, 'Imron', 20000000.00, 6300000.00, 30.00, '2025-08-29 09:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner') DEFAULT 'admin',
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
(3, 'imron', '$2y$10$wT3I1NzIT4SgxYpDhrzqLOd1iI7yqY9MijRZOr5yeRZ4u16JTYZFa', 'owner', 'Imron', 'imron@tobaqontrans.com', '2025-08-30 10:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `address`, `phone`, `email`, `created_at`) VALUES
(1, 'PT Vendor A', 'Jl. Raya Industri No.10', '08121212121', 'vendora@vendor.com', '2025-08-29 09:09:22'),
(2, 'CV Vendor B', 'Jl. Raya Dagang No.5', '08122334455', 'vendorb@vendor.com', '2025-08-29 09:09:22');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jo_number` (`jo_number`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `job_order_items`
--
ALTER TABLE `job_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_id` (`job_order_id`),
  ADD KEY `item_id` (`item_id`);

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
-- Indexes for table `payments_request`
--
ALTER TABLE `payments_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_id` (`job_order_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `bank_account_id` (`bank_account_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_orders`
--
ALTER TABLE `job_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_order_items`
--
ALTER TABLE `job_order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments_customer`
--
ALTER TABLE `payments_customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments_request`
--
ALTER TABLE `payments_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profit_sharing`
--
ALTER TABLE `profit_sharing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `job_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `job_order_items`
--
ALTER TABLE `job_order_items`
  ADD CONSTRAINT `job_order_items_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

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
-- Constraints for table `payments_request`
--
ALTER TABLE `payments_request`
  ADD CONSTRAINT `payments_request_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `payments_request_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `payments_request_ibfk_3` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`);

--
-- Constraints for table `profit_sharing`
--
ALTER TABLE `profit_sharing`
  ADD CONSTRAINT `profit_sharing_ibfk_1` FOREIGN KEY (`job_order_id`) REFERENCES `job_orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
