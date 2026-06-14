-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 06:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `golden_trading`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `department_id`, `name`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Explosives (per kg)', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(2, 1, 'Salt production', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(3, 1, 'Sales', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(4, 1, 'Government expenses', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(5, 1, 'Office salaries', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(6, 1, 'Mine labor', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(7, 1, 'Langar', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(8, 1, 'Medical', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(9, 1, 'Diesel and petrol', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(10, 1, 'Agents', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(11, 4, 'Expenses and salaries', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(12, 4, 'Diesel', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(13, 4, 'Fish feed', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(14, 4, 'Fertilizer', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(15, 4, 'Electricity', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(16, 4, 'Seed', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(17, 4, 'Mess', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(18, 4, 'Medical', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(19, 4, 'Petrol', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(20, 4, 'Other costs', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(21, 3, 'Salt', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(22, 3, 'Vehicle rent (to Chakwal)', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(23, 3, 'Daily labor', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(24, 3, 'Loading labor', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(25, 3, 'Plant labor (180 per ton)', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(26, 3, 'Mess (daily)', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(27, 3, 'Iodine', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(28, 3, 'Bardana packing', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(29, 3, 'Electricity', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(30, 3, 'Petrol', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(31, 3, 'Medicine', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(32, 3, 'Packing material', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(33, 3, 'Salt packing', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(34, 2, 'General expenses', NULL, NULL, '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(35, 1, 'name', 'liter', 'masd', '2025-11-28 22:58:16', '2025-11-28 22:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `department_id`, `name`, `code`, `phone`, `address`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Gulistan (16)', 'GUL16', NULL, NULL, NULL, '2025-11-22 01:29:24', '2025-11-22 01:29:24'),
(2, NULL, 'Haji Sardar and Akhtar (7 Num Mine)', 'HS7', NULL, NULL, NULL, '2025-11-22 01:29:24', '2025-11-22 01:29:24'),
(3, NULL, 'Muhammad Yousaf (14 Num Mine)', 'MY14', NULL, NULL, NULL, '2025-11-22 01:29:24', '2025-11-22 01:29:24'),
(4, NULL, 'Baqir (Mine No 4)', 'BAQ4', NULL, NULL, NULL, '2025-11-22 01:29:24', '2025-11-22 01:29:24'),
(5, 1, 'name', 'csut-02', '0978675123', 'kljkl asdklfhjk asdklfjaskl f', 'ladjfkasdnklas', '2025-11-29 11:54:06', '2025-11-29 11:54:06'),
(6, 1, 'asd', 'cust-03', '03445566158', 'Rawalpindi', 'asd', '2025-12-01 13:13:48', '2025-12-01 13:13:48');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `key`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'salt_mines', 'Salt Mines (Sahadat Ali Khan and Sons)', 'Salt Mines', '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(2, 'self_expenses', 'Self Expenses (Sardar Ali Raza)', 'Self Expenses', '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(3, 'indus_salt_plant', 'Indus Salt Plant (Talagang Road, Chakwal)', 'Indus Salt Plant', '2025-11-22 01:29:23', '2025-11-22 01:29:23'),
(4, 'agriculture', 'Agriculture Department', 'Agriculture', '2025-11-22 01:29:23', '2025-11-22 01:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `spent_at` date NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'PKR',
  `note` text DEFAULT NULL,
  `receipt` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `department_id`, `category_id`, `amount`, `spent_at`, `currency`, `note`, `receipt`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 2000.00, '2025-11-27', 'PKR', 'asd', 'receipts/BcQxMTm1iNFJAXNDYiPfoChavWtoL5ECeO8rzE25.pdf', 2, '2025-11-29 00:13:25', '2025-11-29 00:13:39'),
(3, 1, 10, 4555.00, '2026-01-10', 'PKR', 'asd asd', NULL, 2, '2026-01-10 03:40:37', '2026-01-10 03:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `expense_attachments`
--

CREATE TABLE `expense_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'PKR',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `department_id`, `customer_id`, `invoice_number`, `invoice_date`, `description`, `subtotal`, `tax`, `total`, `currency`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'INV-2025-00002', '2025-12-03', 'ads', 27000.00, 223.00, 27223.00, 'PKR', 2, '2025-12-01 13:32:12', '2025-12-01 14:30:07'),
(2, 1, 4, 'INV-2025-00003', '2025-12-04', 'asd', 684.00, 10.00, 694.00, 'PKR', 2, '2025-12-03 22:20:19', '2025-12-03 22:20:19'),
(3, 1, 6, 'INV-2025-00004', '2025-12-06', ',.djahskdj', 460.00, 11.00, 471.00, 'PKR', 2, '2025-12-06 04:16:53', '2025-12-06 04:16:53'),
(4, 1, NULL, 'INV-2025-00005', '2025-12-11', 'asd', 71.00, 90.00, 161.00, 'PKR', 2, '2025-12-11 12:53:44', '2025-12-11 12:53:44'),
(5, 1, 6, 'INV-2026-00001', '2026-01-10', NULL, 45.00, 5.00, 50.00, 'PKR', 2, '2026-01-10 03:45:30', '2026-01-10 03:47:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_attachments`
--

CREATE TABLE `invoice_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_attachments`
--

INSERT INTO `invoice_attachments` (`id`, `invoice_id`, `filename`, `path`, `mime`, `size`, `created_at`, `updated_at`) VALUES
(2, 1, 'cv.pdf', 'invoices/1/F5H7eXnL75TwYQdISr48atMIPFzQeoOrXKKEbKXr.pdf', 'application/pdf', 110266, '2025-12-01 14:30:07', '2025-12-01 14:30:07'),
(3, 2, 'cv.pdf', 'invoices/2/nJk9HQOKQsOtjWhhVFUiGm7xyadhbRY1Oq75caL3.pdf', 'application/pdf', 110266, '2025-12-03 22:20:19', '2025-12-03 22:20:19'),
(4, 3, 'cv.pdf', 'invoices/3/p91zwCHP56yJoL9CqmlEdM3MAF6YWOTjojeiIzIN.pdf', 'application/pdf', 110266, '2025-12-06 04:16:54', '2025-12-06 04:16:54'),
(5, 4, 'Shahrukh_Mehmood CV.pdf', 'invoices/4/q3P5svnVEIThQuT7Uw40acuGrhBsOvMJUknyTx0W.pdf', 'application/pdf', 99815, '2025-12-11 12:53:45', '2025-12-11 12:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT 1.000,
  `rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `rate`, `total`, `created_at`, `updated_at`) VALUES
(3, 1, 'asd', 54.000, 500.00, 27000.00, '2025-12-01 14:30:07', '2025-12-01 14:30:07'),
(4, 2, 'ashjkdh', 8.000, 7.50, 60.00, '2025-12-03 22:20:19', '2025-12-03 22:20:19'),
(5, 2, 'sda', 78.000, 8.00, 624.00, '2025-12-03 22:20:19', '2025-12-03 22:20:19'),
(6, 3, '22', 1.000, 410.00, 410.00, '2025-12-06 04:16:53', '2025-12-06 04:16:53'),
(7, 3, 'zxc', 2.000, 25.00, 50.00, '2025-12-06 04:16:53', '2025-12-06 04:16:53'),
(8, 4, 'as', 4.000, 5.00, 20.00, '2025-12-11 12:53:44', '2025-12-11 12:53:44'),
(9, 4, 'asd', 1.000, 51.00, 51.00, '2025-12-11 12:53:44', '2025-12-11 12:53:44'),
(12, 5, 'asd', 5.000, 5.00, 25.00, '2026-01-10 03:47:22', '2026-01-10 03:47:22'),
(13, 5, 'asd', 2.000, 10.00, 20.00, '2026-01-10 03:47:22', '2026-01-10 03:47:22');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_22_060516_create_departments_table', 2),
(5, '2025_11_22_060529_create_categories_table', 2),
(6, '2025_11_22_060537_create_expenses_table', 2),
(7, '2025_11_22_060546_create_expense_attachments_table', 2),
(8, '2025_11_22_060554_create_customers_table', 2),
(9, '2025_11_22_060600_create_invoices_table', 2),
(10, '2025_11_22_060611_create_invoice_items_table', 2),
(11, '2025_11_22_060701_create_invoice_attachments_table', 2),
(12, '2025_11_22_060713_create_sales_table', 2),
(13, '2025_11_22_060721_create_sale_items_table', 2),
(14, '2025_11_22_060728_create_sale_attachments_table', 2),
(15, '2025_11_22_060735_create_stock_items_table', 2),
(16, '2025_11_22_060742_create_stock_movements_table', 2),
(17, '2025_11_29_051220_add_receipt_to_expenses_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `sale_date` date NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `department_id`, `sale_date`, `total`, `note`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-29', 200.00, 'kasldkjl', 2, '2025-11-29 01:07:41', '2025-11-29 01:07:41'),
(2, 3, '2025-11-29', 55.06, NULL, 2, '2025-11-29 01:08:38', '2025-11-29 01:08:38'),
(3, 1, '2025-12-04', 91.00, 'm,shgdjhkh', 2, '2025-12-03 22:18:16', '2025-12-03 22:18:16'),
(4, 1, '2026-01-10', 50640.00, 'aksd aklsd', 2, '2026-01-10 03:42:10', '2026-01-10 03:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `sale_attachments`
--

CREATE TABLE `sale_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT 1.000,
  `rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `description`, `quantity`, `rate`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, '22', 2.000, 100.00, 200.00, '2025-11-29 01:07:41', '2025-11-29 01:07:41'),
(2, 2, '21', 1.005, 12.00, 12.06, '2025-11-29 01:08:38', '2025-11-29 01:08:38'),
(3, 2, '25', 1.000, 21.00, 21.00, '2025-11-29 01:08:38', '2025-11-29 01:08:38'),
(4, 2, '30', 1.000, 22.00, 22.00, '2025-11-29 01:08:38', '2025-11-29 01:08:38'),
(5, 3, 'asd', 1.000, 88.00, 88.00, '2025-12-03 22:18:16', '2025-12-03 22:18:16'),
(6, 3, 'dfdjkagf', 1.000, 3.00, 3.00, '2025-12-03 22:18:16', '2025-12-03 22:18:16'),
(7, 4, '50kg', 2.000, 40.00, 80.00, '2026-01-10 03:42:10', '2026-01-10 03:42:10'),
(8, 4, '20kg', 632.000, 80.00, 50560.00, '2026-01-10 03:42:10', '2026-01-10 03:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('35qUMomgYNezPBOXow6UGZJaZ0MA4SM4TqWOYkdK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic0l6aWVVVnVFOExZMHlMMFd2eXhRV1lBU2lJSHlvc3Azc1NKQTRqbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2xvZ2luLWJhc2ljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772720644),
('ZizlAo5LCcmkASe6KYO6KM6FNd5hR1rC2XN3UFVX', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOHE5WjJhaHI0emlaSEZSMTB4Z2x5UVJkV2E2aXR5U1JmMHQ2S2luYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1781412971),
('zVXbGypCJKYyYzTQehCzsMc7S0JaikHJy7Kl87mT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZk9FeGI3N3p3ZmlSdTloak9YUktFM1pGM1BpVGd1Z3kyYWZPSzBUdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2xvZ2luLWJhc2ljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771347186);

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE `stock_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(20,3) NOT NULL DEFAULT 0.000,
  `average_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_items`
--

INSERT INTO `stock_items` (`id`, `department_id`, `sku`, `name`, `unit`, `quantity`, `average_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 'asd', 'asd', 'pcs', 89.000, 8.98, '2025-12-01 15:10:47', '2025-12-11 12:55:26'),
(2, 1, 'jkasdh', 'name', 'pcs', 60.000, 5.00, '2025-12-06 04:44:36', '2026-01-10 03:48:58'),
(3, 1, 'kdjnfl', 'name', 'liter', 0.000, 0.00, '2026-01-10 03:48:17', '2026-01-10 03:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_item_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` decimal(20,3) NOT NULL DEFAULT 0.000,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `moved_at` date NOT NULL,
  `note` text DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `stock_item_id`, `department_id`, `type`, `quantity`, `unit_price`, `moved_at`, `note`, `related_id`, `related_type`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'in', 21.000, 12.00, '2025-12-06', 'haskd aisod askdj', NULL, NULL, '2025-12-06 05:16:15', '2025-12-06 05:16:15'),
(2, 1, 1, 'in', 6.000, 32.00, '2025-12-06', 'asdkjhkln asklhd', NULL, NULL, '2025-12-06 06:33:18', '2025-12-06 06:33:18'),
(3, 1, 1, 'out', 1.000, 16.44, '2025-12-06', 'asd', NULL, NULL, '2025-12-06 07:40:02', '2025-12-06 07:40:02'),
(4, 1, 1, 'in', 65.000, 6.00, '2025-12-11', 'aklsj', NULL, NULL, '2025-12-11 12:55:15', '2025-12-11 12:55:15'),
(5, 1, 1, 'out', 2.000, 8.98, '2025-12-11', 'asd', NULL, NULL, '2025-12-11 12:55:26', '2025-12-11 12:55:26'),
(6, 2, 1, 'in', 65.000, 5.00, '2026-01-10', NULL, NULL, NULL, '2026-01-10 03:48:39', '2026-01-10 03:48:39'),
(7, 2, 1, 'out', 5.000, 5.00, '2026-01-10', NULL, NULL, NULL, '2026-01-10 03:48:58', '2026-01-10 03:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-11-18 12:18:49', '$2y$12$w3iWQpNC0LaAZV7AVoNfw.n2klyx9vMF2wTxHsY1BKWPbJRHDXWwy', 'dkImxVJ8Tc', '2025-11-18 12:18:49', '2025-11-18 12:18:49'),
(2, 'Admin User', 'admin@gmail.com', '2025-11-18 12:40:19', '$2y$12$4ZhEHqnl8R4KDInxsTwH2ezDgNAiGQp9nff2lLdwziucIzC4ZmdhK', 'NeKWcximR6KUSFrlkpOhLZKk2Z77cPzsNTc0VFNOBPJyIEBaAjj6EE8n1My7', '2025-11-18 12:40:20', '2025-11-18 12:40:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_department_id_name_unique` (`department_id`,`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_department_id_name_unique` (`department_id`,`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_key_unique` (`key`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_category_id_foreign` (`category_id`),
  ADD KEY `expenses_department_id_spent_at_index` (`department_id`,`spent_at`),
  ADD KEY `expenses_spent_at_index` (`spent_at`);

--
-- Indexes for table `expense_attachments`
--
ALTER TABLE `expense_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_attachments_expense_id_foreign` (`expense_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_department_id_invoice_date_index` (`department_id`,`invoice_date`),
  ADD KEY `invoices_invoice_date_index` (`invoice_date`);

--
-- Indexes for table `invoice_attachments`
--
ALTER TABLE `invoice_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_attachments_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_department_id_foreign` (`department_id`),
  ADD KEY `sales_sale_date_index` (`sale_date`);

--
-- Indexes for table `sale_attachments`
--
ALTER TABLE `sale_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_attachments_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_items_department_id_foreign` (`department_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_stock_item_id_foreign` (`stock_item_id`),
  ADD KEY `stock_movements_department_id_foreign` (`department_id`),
  ADD KEY `stock_movements_type_index` (`type`),
  ADD KEY `stock_movements_moved_at_index` (`moved_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expense_attachments`
--
ALTER TABLE `expense_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_attachments`
--
ALTER TABLE `invoice_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_attachments`
--
ALTER TABLE `sale_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_attachments`
--
ALTER TABLE `expense_attachments`
  ADD CONSTRAINT `expense_attachments_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_attachments`
--
ALTER TABLE `invoice_attachments`
  ADD CONSTRAINT `invoice_attachments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_attachments`
--
ALTER TABLE `sale_attachments`
  ADD CONSTRAINT `sale_attachments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD CONSTRAINT `stock_items_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
