-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2026 at 11:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rizky_berkah`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `phone`, `discount_percent`, `created_at`) VALUES
(1, 'Budi Sujiwo', '021458874', 12.00, '2026-02-25 06:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_retail` decimal(15,2) NOT NULL,
  `price_wholesale` decimal(15,2) DEFAULT '0.00',
  `min_wholesale_qty` int DEFAULT '0',
  `last_cost` decimal(15,2) NOT NULL,
  `avg_cost` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `barcode`, `name`, `price_retail`, `price_wholesale`, `min_wholesale_qty`, `last_cost`, `avg_cost`, `created_at`) VALUES
(1, '001', '12356744654', 'TAS RANSEL CHARACTER', 260000.00, 120000.00, 5, 250000.00, 250000.00, '2026-02-24 07:25:22'),
(2, 'T SL BLACK', 'T SL BLACK', 'TAS SLEMPANG HITAM', 125000.00, 80000.00, 5, 75000.00, 75000.00, '2026-02-25 04:21:31'),
(3, 'T PNG BLACK', 'TAS PINGGANG HITAM', 'TAS PINGGANG HITAM', 85000.00, 60000.00, 5, 70000.00, 70000.00, '2026-03-04 06:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `invoice_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `warehouse_id`, `invoice_no`, `total`, `created_at`) VALUES
(1, 1, 1, NULL, 2500000.00, '2026-03-25 09:24:27'),
(2, 1, 1, NULL, 1875000.00, '2026-03-26 04:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` int NOT NULL,
  `purchase_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `qty`, `cost_price`, `subtotal`) VALUES
(1, 1, 1, 10, 250000.00, 2500000.00),
(2, 2, 2, 25, 75000.00, 1875000.00);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` int NOT NULL,
  `purchase_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `return_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(15,2) DEFAULT '0.00',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_items`
--

CREATE TABLE `purchase_return_items` (
  `id` int NOT NULL,
  `purchase_return_id` int NOT NULL,
  `purchase_item_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` decimal(15,2) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `invoice_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int NOT NULL,
  `cashier_id` int NOT NULL,
  `member_id` int DEFAULT NULL,
  `total` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` enum('cash','transfer','qris','card') COLLATE utf8mb4_unicode_ci DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_no`, `store_id`, `cashier_id`, `member_id`, `total`, `discount`, `grand_total`, `created_at`, `payment_method`) VALUES
(1, 'INV20260325162533', 2, 3, NULL, 250000.00, 200000.00, 50000.00, '2026-03-25 09:25:33', 'cash'),
(2, 'INV20260325162629', 2, 3, NULL, 250000.00, 240000.00, 10000.00, '2026-03-25 09:26:29', 'cash'),
(3, 'INV20260325165937', 2, 3, NULL, 250000.00, 200000.00, 50000.00, '2026-03-25 09:59:37', 'cash'),
(4, 'INV20260325170937', 2, 3, NULL, 250000.00, 200000.00, 50000.00, '2026-03-25 10:09:37', 'cash'),
(5, 'INV20260325171421', 2, 3, NULL, 250000.00, 200000.00, 50000.00, '2026-03-25 10:14:21', 'cash'),
(6, 'INV20260325173240', 2, 3, NULL, 50000.00, 0.00, 50000.00, '2026-03-25 10:32:40', 'cash'),
(7, 'INV20260325173920', 2, 3, NULL, 260000.00, 0.00, 260000.00, '2026-03-25 10:39:20', 'cash'),
(8, 'INV20260326112726', 2, 3, NULL, 250000.00, 0.00, 250000.00, '2026-03-26 04:27:26', 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int NOT NULL,
  `sale_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `qty`, `price`, `cost`, `subtotal`) VALUES
(1, 1, 1, 1, 250000.00, 250000.00, 250000.00),
(2, 2, 1, 1, 250000.00, 250000.00, 250000.00),
(3, 3, 1, 1, 250000.00, 250000.00, 250000.00),
(4, 4, 1, 1, 250000.00, 250000.00, 250000.00),
(5, 5, 1, 1, 250000.00, 250000.00, 250000.00),
(6, 6, 1, 1, 50000.00, 250000.00, 50000.00),
(7, 7, 1, 1, 260000.00, 250000.00, 260000.00),
(8, 8, 2, 2, 125000.00, 75000.00, 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` int NOT NULL,
  `sale_id` int NOT NULL,
  `store_id` int NOT NULL,
  `created_by` int NOT NULL,
  `total_refund` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_items`
--

CREATE TABLE `sale_return_items` (
  `id` int NOT NULL,
  `sale_return_id` int NOT NULL,
  `sale_item_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `stock_status` enum('sellable','damaged') COLLATE utf8mb4_unicode_ci DEFAULT 'sellable',
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `location_type` enum('warehouse','store') COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int NOT NULL,
  `stock_status` enum('sellable','damaged') COLLATE utf8mb4_unicode_ci DEFAULT 'sellable',
  `qty` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `location_type`, `location_id`, `stock_status`, `qty`, `updated_at`) VALUES
(1, 1, 'warehouse', 1, 'sellable', 0, '2026-03-25 10:32:02'),
(2, 1, 'store', 2, 'sellable', 3, '2026-03-25 10:39:20'),
(4, 2, 'warehouse', 1, 'sellable', 10, '2026-03-26 04:25:22'),
(5, 2, 'store', 2, 'sellable', 13, '2026-03-26 04:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `from_type` enum('warehouse','store') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_id` int DEFAULT NULL,
  `to_type` enum('warehouse','store') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_id` int DEFAULT NULL,
  `qty` int NOT NULL,
  `stock_status` enum('sellable','damaged') COLLATE utf8mb4_unicode_ci DEFAULT 'sellable',
  `movement_type` enum('purchase','sale','transfer','adjustment','purchase_return','sale_return') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `from_type`, `from_id`, `to_type`, `to_id`, `qty`, `stock_status`, `movement_type`, `reference_id`, `created_at`, `note`, `user_id`) VALUES
(1, 1, NULL, NULL, 'warehouse', 1, 10, 'sellable', 'purchase', 1, '2026-03-25 09:24:27', NULL, NULL),
(2, 1, 'warehouse', 1, 'store', 2, 5, 'sellable', 'transfer', 1, '2026-03-25 09:25:06', NULL, NULL),
(3, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 1, '2026-03-25 09:25:33', NULL, NULL),
(4, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 2, '2026-03-25 09:26:29', NULL, NULL),
(5, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 3, '2026-03-25 09:59:37', NULL, NULL),
(6, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 4, '2026-03-25 10:09:37', NULL, NULL),
(7, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 5, '2026-03-25 10:14:21', NULL, NULL),
(8, 1, 'warehouse', 1, 'store', 2, 5, 'sellable', 'transfer', 2, '2026-03-25 10:32:02', NULL, NULL),
(9, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 6, '2026-03-25 10:32:40', NULL, NULL),
(10, 1, 'store', 2, NULL, NULL, 1, 'sellable', 'sale', 7, '2026-03-25 10:39:20', NULL, NULL),
(11, 2, NULL, NULL, 'warehouse', 1, 25, 'sellable', 'purchase', 2, '2026-03-26 04:24:32', NULL, NULL),
(12, 2, 'warehouse', 1, 'store', 2, 15, 'sellable', 'transfer', 3, '2026-03-26 04:25:22', NULL, NULL),
(13, 2, 'store', 2, NULL, NULL, 2, 'sellable', 'sale', 8, '2026-03-26 04:27:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `address`, `created_at`) VALUES
(2, 'TOKO RB ITC', 'ITC Gembong LT3 Blk E 23', '2026-02-21 13:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
(1, 'Ahmad Kozinudin', '456123', 'Benjeng Timur 248B', '2026-02-25 09:15:49'),
(2, 'Don Juan Alejandro', '0845125452', 'JL. Daan Mogot No. 32, Sidoarjo', '2026-03-04 06:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int NOT NULL,
  `from_type` enum('warehouse','store') COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` int NOT NULL,
  `to_type` enum('warehouse','store') COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_id` int NOT NULL,
  `total_items` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `from_type`, `from_id`, `to_type`, `to_id`, `total_items`, `created_at`) VALUES
(1, 'warehouse', 1, 'store', 2, 1, '2026-03-25 09:25:06'),
(2, 'warehouse', 1, 'store', 2, 1, '2026-03-25 10:32:02'),
(3, 'warehouse', 1, 'store', 2, 1, '2026-03-26 04:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_items`
--

CREATE TABLE `transfer_items` (
  `id` int NOT NULL,
  `transfer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_items`
--

INSERT INTO `transfer_items` (`id`, `transfer_id`, `product_id`, `qty`) VALUES
(1, 1, 1, 5),
(2, 2, 1, 5),
(3, 3, 2, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier') COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `store_id`, `created_at`, `is_active`, `last_login`) VALUES
(1, 'Endang Wahyu', 'admin@toko.com', '$2y$10$t19CHNrCD7.ALxLfWkNxjO35E/pKQp3x1Tsye9F/5Sz9N84Anzjpq', 'admin', NULL, '2026-02-20 03:09:25', 1, '2026-03-05 03:00:36'),
(3, 'Lala Laylatul', 'lala.laylatul@toko.com', '$2y$10$xOBwd7BQtWa5XpOe0m6fMeaUzP1ac3ykN/f8z0DXjzW3WTu05b2qC', 'cashier', 2, '2026-03-05 03:18:52', 1, '2026-03-26 09:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `address`, `created_at`) VALUES
(1, 'Gudang A', 'Banyu Urip Kidul V/32', '2026-02-21 14:08:19'),
(3, 'Gudang B', 'Petemon Kidul No.23', '2026-02-22 08:43:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pr_purchase` (`purchase_id`);

--
-- Indexes for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_id` (`purchase_return_id`),
  ADD KEY `purchase_item_id` (`purchase_item_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `idx_store_id` (`store_id`),
  ADD KEY `idx_cashier_id` (`cashier_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sale_id` (`sale_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_return_id` (`sale_return_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_stock` (`product_id`,`location_type`,`location_id`,`stock_status`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_location` (`location_type`,`location_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_from` (`from_type`,`from_id`),
  ADD KEY `idx_to` (`to_type`,`to_id`),
  ADD KEY `idx_reference` (`reference_id`),
  ADD KEY `fk_stock_user` (`user_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_items`
--
ALTER TABLE `transfer_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfer_items`
--
ALTER TABLE `transfer_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `fk_pr_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD CONSTRAINT `purchase_return_items_ibfk_1` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`),
  ADD CONSTRAINT `purchase_return_items_ibfk_2` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `fk_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `fk_stock_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
