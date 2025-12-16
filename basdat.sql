-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 03:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basdat`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `pasien_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_booking` date DEFAULT NULL,
  `sesi` int(11) DEFAULT NULL COMMENT 'Session: 1=08:00-10:00, 2=10:00-12:00, 3=13:00-15:00, 4=15:00-17:00',
  `status_pembayaran` varchar(255) NOT NULL DEFAULT 'belum_bayar',
  `status_tes` varchar(255) NOT NULL DEFAULT 'menunggu',
  `alasan_reject` text DEFAULT NULL COMMENT 'Reason for rejection'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `pasien_id`, `cabang_id`, `tanggal_booking`, `sesi`, `status_pembayaran`, `status_tes`, `alasan_reject`) VALUES
(5, 2, 1, '2025-12-17', 1, 'confirmed', 'completed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
  `nama_cabang` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`cabang_id`, `nama_cabang`, `alamat`) VALUES
(1, 'Cabang A', 'Jl. Sudirman No. 1'),
(2, 'Cabang B', 'Jl. Thamrin No. 2'),
(3, 'Cabang C', 'Jl. Gatot Subroto No. 3');

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
-- Table structure for table `detail_booking`
--

CREATE TABLE `detail_booking` (
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `tes_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_booking`
--

INSERT INTO `detail_booking` (`booking_id`, `tes_id`) VALUES
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `detail_tes`
--

CREATE TABLE `detail_tes` (
  `tes_id` bigint(20) UNSIGNED NOT NULL,
  `param_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `dokter_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `spesialisasi` varchar(255) DEFAULT NULL
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
-- Table structure for table `hasil_tes_header`
--

CREATE TABLE `hasil_tes_header` (
  `hasil_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_input` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hasil_tes_header`
--

INSERT INTO `hasil_tes_header` (`hasil_id`, `booking_id`, `dibuat_oleh`, `tanggal_input`) VALUES
(3, 5, 1, '2025-12-18');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_tes_value`
--

CREATE TABLE `hasil_tes_value` (
  `hasil_value_id` bigint(20) UNSIGNED NOT NULL,
  `hasil_id` bigint(20) UNSIGNED NOT NULL,
  `param_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_hasil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hasil_tes_value`
--

INSERT INTO `hasil_tes_value` (`hasil_value_id`, `hasil_id`, `param_id`, `nilai_hasil`) VALUES
(3, 3, 42, '24');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tes`
--

CREATE TABLE `jenis_tes` (
  `tes_id` bigint(20) UNSIGNED NOT NULL,
  `nama_tes` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `persiapan_khusus` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_tes`
--

INSERT INTO `jenis_tes` (`tes_id`, `nama_tes`, `deskripsi`, `harga`, `persiapan_khusus`) VALUES
(2, 'Tes Rontgen Gigi (Panoramic)', 'Deskripsi Tes Rontgen Gigi (Panoramic)', 150000.00, NULL),
(3, 'Tes Rontgen Gigi (Water\'s Foto)', 'Deskripsi Tes Rontgen Gigi (Water\'s Foto)', 200000.00, NULL),
(4, 'Tes Urine', 'Deskripsi Tes Urine', 50000.00, NULL),
(5, 'Tes Kehamilan (Anti-Rubella lgG)', 'Deskripsi Tes Kehamilan (Anti-Rubella lgG)', 120000.00, NULL),
(6, 'Tes Kehamilan (Anti-CMV lgG)', 'Deskripsi Tes Kehamilan (Anti-CMV lgG)', 120000.00, NULL),
(7, 'Tes Kehamilan (Anti-HSV1 lgG)', 'Deskripsi Tes Kehamilan (Anti-HSV1 lgG)', 120000.00, NULL),
(8, 'Tes Darah (Hemoglobin)', 'Deskripsi Tes Darah (Hemoglobin)', 75000.00, NULL),
(9, 'Tes Darah (Golongan Darah)', 'Deskripsi Tes Darah (Golongan Darah)', 90000.00, NULL),
(10, 'Tes Darah (Agregasi Trombosit)', 'Deskripsi Tes Darah (Agregasi Trombosit)', 100000.00, NULL);

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
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_activity`
--

INSERT INTO `log_activity` (`log_id`, `user_id`, `action`, `resource_type`, `resource_id`, `created_at`) VALUES
(1, 1, 'User logged in', NULL, NULL, '2025-12-15 12:26:29'),
(2, 1, 'User logged out', NULL, NULL, '2025-12-15 12:30:18'),
(3, 1, 'User logged in', NULL, NULL, '2025-12-15 12:30:25'),
(4, 1, 'User logged out', NULL, NULL, '2025-12-15 12:32:19'),
(5, 1, 'User logged in', NULL, NULL, '2025-12-15 12:32:26'),
(6, 1, 'User logged out', NULL, NULL, '2025-12-15 12:33:44'),
(7, 1, 'User logged in', NULL, NULL, '2025-12-15 12:33:52'),
(8, 1, 'User logged out', NULL, NULL, '2025-12-15 12:36:35'),
(9, 1, 'User logged in', NULL, NULL, '2025-12-15 12:36:42'),
(10, 1, 'User logged out', NULL, NULL, '2025-12-15 12:38:11'),
(11, 1, 'User logged in', NULL, NULL, '2025-12-15 12:38:17'),
(12, 1, 'User logged out', NULL, NULL, '2025-12-15 12:39:11'),
(13, 1, 'User logged in', NULL, NULL, '2025-12-15 12:39:17'),
(14, 1, 'User logged out', NULL, NULL, '2025-12-15 12:41:51'),
(15, 1, 'User logged in', NULL, NULL, '2025-12-15 12:41:56'),
(16, 1, 'User logged out', NULL, NULL, '2025-12-15 12:44:25'),
(17, 1, 'User logged in', NULL, NULL, '2025-12-15 12:44:32'),
(18, 1, 'User logged out', NULL, NULL, '2025-12-15 12:48:13'),
(19, 1, 'User logged in', NULL, NULL, '2025-12-15 12:48:19'),
(20, 1, 'Payment verified for booking ID: 1', NULL, NULL, '2025-12-15 12:48:36'),
(21, 1, 'Payment verified for booking ID: 1', NULL, NULL, '2025-12-15 13:01:08'),
(22, 1, 'Deleted test: Tes Rontgen Gigi (Dental I CR)', NULL, NULL, '2025-12-15 13:01:31'),
(23, 1, 'Confirmed payment for booking ID: 1', NULL, NULL, '2025-12-15 13:02:24'),
(24, 1, 'User logged out', NULL, NULL, '2025-12-15 13:10:24'),
(25, 1, 'User logged in', NULL, NULL, '2025-12-15 13:10:30'),
(26, 1, 'User logged out', NULL, NULL, '2025-12-15 13:26:40'),
(27, 1, 'User logged in', NULL, NULL, '2025-12-15 13:36:05'),
(28, 1, 'User logged out', NULL, NULL, '2025-12-15 14:13:08'),
(29, 1, 'User logged in', NULL, NULL, '2025-12-15 14:52:10'),
(30, 3, 'User registered', NULL, NULL, '2025-12-16 06:00:25'),
(31, 3, 'User logged in', NULL, NULL, '2025-12-16 06:00:41'),
(32, 3, 'Created booking ID: 2 for session 1', NULL, NULL, '2025-12-16 06:06:53'),
(33, 3, 'booking_sesi:1;booking_id:2', 'booking', NULL, '2025-12-16 06:06:53'),
(34, 3, 'booking_sesi:1;booking_id:2', 'booking', NULL, '2025-12-16 06:06:53'),
(35, 3, 'booking_sesi:1;booking_id:2', 'booking', NULL, '2025-12-16 06:11:14'),
(36, 3, 'Uploaded payment proof for booking ID: 2', NULL, NULL, '2025-12-16 06:11:14'),
(37, 3, 'User logged out', NULL, NULL, '2025-12-16 06:11:25'),
(38, 1, 'User logged in', NULL, NULL, '2025-12-16 06:11:33'),
(39, 1, 'Payment verified for booking ID: 2', NULL, NULL, '2025-12-16 06:11:54'),
(40, 1, 'Confirmed payment for booking ID: 2', NULL, NULL, '2025-12-16 06:27:15'),
(41, 1, 'Payment verified for booking ID: 2', NULL, NULL, '2025-12-16 06:28:26'),
(42, 1, 'User logged out', NULL, NULL, '2025-12-16 06:28:55'),
(43, 3, 'User logged in', NULL, NULL, '2025-12-16 06:29:08'),
(44, 3, 'User logged out', NULL, NULL, '2025-12-16 06:38:55'),
(45, 1, 'User logged in', NULL, NULL, '2025-12-16 06:39:06'),
(46, 1, 'User logged out', NULL, NULL, '2025-12-16 06:48:39'),
(47, 3, 'User logged in', NULL, NULL, '2025-12-16 06:48:55'),
(48, 3, 'User logged out', NULL, NULL, '2025-12-16 07:22:36'),
(49, 1, 'User logged in', NULL, NULL, '2025-12-16 07:22:43'),
(50, 1, 'Uploaded test result for booking ID: 2', NULL, NULL, '2025-12-16 08:47:09'),
(51, 1, 'User logged out', NULL, NULL, '2025-12-16 08:47:17'),
(52, 3, 'User logged in', NULL, NULL, '2025-12-16 08:47:25'),
(53, 3, 'User logged out', NULL, NULL, '2025-12-16 09:05:05'),
(54, 1, 'User logged in', NULL, NULL, '2025-12-16 09:05:12'),
(55, 1, 'Uploaded test result for booking ID: 2', NULL, NULL, '2025-12-16 09:05:19'),
(56, 1, 'User logged out', NULL, NULL, '2025-12-16 09:05:24'),
(57, 3, 'User logged in', NULL, NULL, '2025-12-16 09:05:31'),
(58, 3, 'User logged out', NULL, NULL, '2025-12-16 09:40:08'),
(59, 1, 'User logged in', NULL, NULL, '2025-12-16 09:40:14'),
(60, 1, 'Uploaded test result for booking ID: 2', NULL, NULL, '2025-12-16 09:40:22'),
(61, 1, 'User logged out', NULL, NULL, '2025-12-16 09:40:29'),
(62, 3, 'User logged in', NULL, NULL, '2025-12-16 09:40:42'),
(63, 3, 'User logged out', NULL, NULL, '2025-12-16 09:53:15'),
(64, 1, 'User logged in', NULL, NULL, '2025-12-16 09:53:23'),
(65, 1, 'Uploaded test result for booking ID: 2', NULL, NULL, '2025-12-16 09:53:33'),
(66, 1, 'Uploaded test result for booking ID: 2', NULL, NULL, '2025-12-16 09:53:39'),
(67, 1, 'User logged out', NULL, NULL, '2025-12-16 09:53:44'),
(68, 3, 'User logged in', NULL, NULL, '2025-12-16 09:53:56'),
(69, 3, 'User logged in', NULL, NULL, '2025-12-16 14:17:24'),
(70, 3, 'User logged out', NULL, NULL, '2025-12-16 14:18:06'),
(71, 3, 'User logged in', NULL, NULL, '2025-12-16 14:20:13'),
(72, 3, 'Created booking ID: 3 for session 1', NULL, NULL, '2025-12-16 14:20:49'),
(73, 3, 'booking_sesi:1;booking_id:3', 'booking', NULL, '2025-12-16 14:20:49'),
(74, 3, 'booking_sesi:1;booking_id:3', 'booking', NULL, '2025-12-16 14:20:49'),
(75, 3, 'booking_sesi:1;booking_id:3', 'booking', NULL, '2025-12-16 14:21:02'),
(76, 3, 'Uploaded payment proof for booking ID: 3', NULL, NULL, '2025-12-16 14:21:02'),
(77, 3, 'User logged out', NULL, NULL, '2025-12-16 14:21:05'),
(78, 1, 'User logged in', NULL, NULL, '2025-12-16 14:21:14'),
(79, 1, 'Confirmed payment for booking ID: 3', NULL, NULL, '2025-12-16 14:21:33'),
(80, 1, 'Payment verified for booking ID: 3', NULL, NULL, '2025-12-16 14:21:38'),
(81, 1, 'Deleted test result for booking ID: 1', NULL, NULL, '2025-12-16 14:23:11'),
(82, 1, 'User logged out', NULL, NULL, '2025-12-16 14:25:45'),
(83, 3, 'User logged in', NULL, NULL, '2025-12-16 14:43:29'),
(84, 3, 'Created booking ID: 4 for session 1', NULL, NULL, '2025-12-16 14:43:49'),
(85, 3, 'booking_sesi:1;booking_id:4', 'booking', NULL, '2025-12-16 14:43:49'),
(86, 3, 'booking_sesi:1;booking_id:4', 'booking', NULL, '2025-12-16 14:43:49'),
(87, 3, 'User logged out', NULL, NULL, '2025-12-16 14:44:01'),
(88, 1, 'User logged in', NULL, NULL, '2025-12-16 14:44:10'),
(89, 1, 'User logged out', NULL, NULL, '2025-12-16 14:44:31'),
(90, 3, 'User logged in', NULL, NULL, '2025-12-16 14:45:02'),
(91, 3, 'Created booking ID: 5 for session 1', NULL, NULL, '2025-12-16 14:45:16'),
(92, 3, 'booking_sesi:1;booking_id:5', 'booking', NULL, '2025-12-16 14:45:16'),
(93, 3, 'booking_sesi:1;booking_id:5', 'booking', NULL, '2025-12-16 14:45:16'),
(94, 3, 'booking_sesi:1;booking_id:5', 'booking', NULL, '2025-12-16 14:45:28'),
(95, 3, 'Uploaded payment proof for booking ID: 5', NULL, NULL, '2025-12-16 14:45:28'),
(96, 3, 'User logged out', NULL, NULL, '2025-12-16 14:45:31'),
(97, 1, 'User logged in', NULL, NULL, '2025-12-16 14:45:42'),
(98, 1, 'Confirmed payment for booking ID: 5', NULL, NULL, '2025-12-16 14:45:53'),
(99, 1, 'Payment verified for booking ID: 5', NULL, NULL, '2025-12-16 14:45:58'),
(100, 1, 'Uploaded test result for booking ID: 5', NULL, NULL, '2025-12-16 14:46:19'),
(101, 1, 'User logged out', NULL, NULL, '2025-12-16 14:46:28'),
(102, 3, 'User logged in', NULL, NULL, '2025-12-16 14:46:36'),
(103, 3, 'User logged out', NULL, NULL, '2025-12-16 14:47:25'),
(104, 1, 'User logged in', NULL, NULL, '2025-12-16 14:47:31'),
(105, 1, 'User logged out', NULL, NULL, '2025-12-16 14:54:27'),
(106, 3, 'User logged in', NULL, NULL, '2025-12-16 14:54:33'),
(107, 3, 'User logged out', NULL, NULL, '2025-12-16 14:55:02'),
(108, 1, 'User logged in', NULL, NULL, '2025-12-16 14:55:08');

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
(4, '2025_09_22_000001_create_roles_table', 1),
(5, '2025_09_22_000002_create_users_table', 1),
(6, '2025_09_22_000003_create_cabangs_table', 1),
(7, '2025_09_22_000004_create_pasiens_table', 1),
(8, '2025_09_22_000005_create_jenis_tes_table', 1),
(9, '2025_09_22_000006_create_parameter_tes_table', 1),
(10, '2025_09_22_000007_create_bookings_table', 1),
(11, '2025_09_22_000008_create_detail_booking_table', 1),
(12, '2025_09_22_000009_create_detail_tes_table', 1),
(13, '2025_09_22_000010_create_pembayaran_table', 1),
(14, '2025_09_22_000011_create_hasil_tes_header_table', 1),
(15, '2025_09_22_000012_create_hasil_tes_value_table', 1),
(16, '2025_09_22_000013_create_riwayat_booking_table', 1),
(17, '2025_09_22_000014_create_dokter_table', 1),
(18, '2025_09_22_000015_create_staf_table', 1),
(19, '2025_09_22_000016_create_log_activity_table', 1),
(20, '2025_10_08_003753_add_sesi_to_booking_table', 1),
(21, '2025_10_08_003831_add_fields_to_pembayaran_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parameter_tes`
--

CREATE TABLE `parameter_tes` (
  `param_id` bigint(20) UNSIGNED NOT NULL,
  `tes_id` bigint(20) UNSIGNED NOT NULL,
  `nama_parameter` varchar(255) NOT NULL,
  `satuan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parameter_tes`
--

INSERT INTO `parameter_tes` (`param_id`, `tes_id`, `nama_parameter`, `satuan`) VALUES
(1, 8, 'Hemoglobin', 'g/dL'),
(2, 8, 'WBC Count', '×10³/µL'),
(29, 7, 'Hemoglobin', 'g/dL'),
(30, 8, 'Golongan Darah', NULL),
(31, 8, 'Rhesus', NULL),
(32, 9, 'Agregasi Trombosit', '%'),
(33, 3, 'Warna Urine', NULL),
(34, 3, 'pH Urine', NULL),
(35, 3, 'Protein', 'mg/dL'),
(36, 3, 'Glukosa', 'mg/dL'),
(37, 3, 'Keton', 'mg/dL'),
(38, 4, 'Anti-Rubella IgG', 'IU/mL'),
(39, 5, 'Anti-CMV IgG', 'AU/mL'),
(40, 6, 'Anti-HSV1 IgG', 'Index'),
(42, 2, 'Hasil Rontgen Waters', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `pasien_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`pasien_id`, `nama`, `tgl_lahir`, `email`, `no_hp`, `user_id`) VALUES
(1, 'Budi', '1990-01-01', 'budi@example.com', '08123456789', 2),
(2, 'John Doe', '2001-01-11', 'johndoe@gmail.com', '0812345678', 3);

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
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(12,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `bukti_pembayaran` varchar(255) DEFAULT NULL COMMENT 'Payment proof file path',
  `tanggal_upload` timestamp NULL DEFAULT NULL COMMENT 'Upload date',
  `tanggal_konfirmasi` timestamp NULL DEFAULT NULL COMMENT 'Confirmation date',
  `alasan_reject` text DEFAULT NULL COMMENT 'Rejection reason',
  `bukti_path` varchar(255) DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `booking_id`, `jumlah`, `metode_bayar`, `status`, `bukti_pembayaran`, `tanggal_upload`, `tanggal_konfirmasi`, `alasan_reject`, `bukti_path`, `tanggal_bayar`) VALUES
(5, 5, 155000.00, 'transfer', 'confirmed', 'payment_proofs/payment_proof_5_1765896328.pdf', '2025-12-16 07:45:28', '2025-12-16 07:45:58', NULL, NULL, '2025-12-16');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_booking`
--

CREATE TABLE `riwayat_booking` (
  `history_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `previous_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `changed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `name`, `slug`) VALUES
(1, 'Administrator', 'admin'),
(2, 'User', 'user'),
(3, 'Pasien', 'pasien');

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
('bP6opETnoV7z4WKuzh5YbcIK2dLMeGoxBr9vID3D', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoibEpWNXA0V2pOQkdOTUxyVHBLTTJtZWpOYWtSeEdTSTE1UE4xbEhLVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wYXltZW50cyI7fXM6NzoidXNlcl9pZCI7aToxO3M6ODoidXNlcm5hbWUiO3M6NToiYWRtaW4iO3M6NDoicm9sZSI7czo1OiJhZG1pbiI7czo5OiJyb2xlX25hbWUiO3M6NToiYWRtaW4iO30=', 1765896913);

-- --------------------------------------------------------

--
-- Table structure for table `staf`
--

CREATE TABLE `staf` (
  `staf_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password_hash`, `role_id`) VALUES
(1, 'admin', '$2y$12$r6M/J3GE0rxZlgOmFVt9B.FAWKVZTAktQoD10GXb43rIa1JQzulri', 1),
(2, 'user', '$2y$12$Gshw.FNjiU1b1tl1jUhdUOG.DZ9vhpA2W.9DbYrsb3VGzkgsL1fZK', 2),
(3, 'test', '$2y$12$SKhZ2pjMWqbFrVDifgDvRejNo1x3nED3F2TnZQPYojXqg63lCtGVm', 3);

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
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `booking_pasien_id_foreign` (`pasien_id`),
  ADD KEY `booking_cabang_id_foreign` (`cabang_id`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`cabang_id`);

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
-- Indexes for table `detail_booking`
--
ALTER TABLE `detail_booking`
  ADD PRIMARY KEY (`booking_id`,`tes_id`),
  ADD KEY `detail_booking_tes_id_foreign` (`tes_id`);

--
-- Indexes for table `detail_tes`
--
ALTER TABLE `detail_tes`
  ADD PRIMARY KEY (`tes_id`,`param_id`),
  ADD KEY `detail_tes_param_id_foreign` (`param_id`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`dokter_id`),
  ADD KEY `dokter_cabang_id_foreign` (`cabang_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hasil_tes_header`
--
ALTER TABLE `hasil_tes_header`
  ADD PRIMARY KEY (`hasil_id`),
  ADD KEY `hasil_tes_header_booking_id_foreign` (`booking_id`),
  ADD KEY `hasil_tes_header_dibuat_oleh_foreign` (`dibuat_oleh`);

--
-- Indexes for table `hasil_tes_value`
--
ALTER TABLE `hasil_tes_value`
  ADD PRIMARY KEY (`hasil_value_id`),
  ADD KEY `hasil_tes_value_hasil_id_foreign` (`hasil_id`),
  ADD KEY `hasil_tes_value_param_id_foreign` (`param_id`);

--
-- Indexes for table `jenis_tes`
--
ALTER TABLE `jenis_tes`
  ADD PRIMARY KEY (`tes_id`);

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
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_activity_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parameter_tes`
--
ALTER TABLE `parameter_tes`
  ADD PRIMARY KEY (`param_id`),
  ADD KEY `parameter_tes_tes_id_foreign` (`tes_id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`pasien_id`),
  ADD KEY `pasien_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `pembayaran_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `riwayat_booking`
--
ALTER TABLE `riwayat_booking`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `riwayat_booking_booking_id_foreign` (`booking_id`),
  ADD KEY `riwayat_booking_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_slug_unique` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staf`
--
ALTER TABLE `staf`
  ADD PRIMARY KEY (`staf_id`),
  ADD KEY `staf_user_id_foreign` (`user_id`),
  ADD KEY `staf_cabang_id_foreign` (`cabang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_username_unique` (`username`),
  ADD KEY `user_role_id_foreign` (`role_id`);

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
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cabang`
--
ALTER TABLE `cabang`
  MODIFY `cabang_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `dokter_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_tes_header`
--
ALTER TABLE `hasil_tes_header`
  MODIFY `hasil_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hasil_tes_value`
--
ALTER TABLE `hasil_tes_value`
  MODIFY `hasil_value_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_tes`
--
ALTER TABLE `jenis_tes`
  MODIFY `tes_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `parameter_tes`
--
ALTER TABLE `parameter_tes`
  MODIFY `param_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `pasien_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `riwayat_booking`
--
ALTER TABLE `riwayat_booking`
  MODIFY `history_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staf`
--
ALTER TABLE `staf`
  MODIFY `staf_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`cabang_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_pasien_id_foreign` FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`pasien_id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_booking`
--
ALTER TABLE `detail_booking`
  ADD CONSTRAINT `detail_booking_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_booking_tes_id_foreign` FOREIGN KEY (`tes_id`) REFERENCES `jenis_tes` (`tes_id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_tes`
--
ALTER TABLE `detail_tes`
  ADD CONSTRAINT `detail_tes_param_id_foreign` FOREIGN KEY (`param_id`) REFERENCES `parameter_tes` (`param_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_tes_tes_id_foreign` FOREIGN KEY (`tes_id`) REFERENCES `jenis_tes` (`tes_id`) ON DELETE CASCADE;

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`cabang_id`) ON DELETE CASCADE;

--
-- Constraints for table `hasil_tes_header`
--
ALTER TABLE `hasil_tes_header`
  ADD CONSTRAINT `hasil_tes_header_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_tes_header_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `hasil_tes_value`
--
ALTER TABLE `hasil_tes_value`
  ADD CONSTRAINT `hasil_tes_value_hasil_id_foreign` FOREIGN KEY (`hasil_id`) REFERENCES `hasil_tes_header` (`hasil_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasil_tes_value_param_id_foreign` FOREIGN KEY (`param_id`) REFERENCES `parameter_tes` (`param_id`) ON DELETE CASCADE;

--
-- Constraints for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD CONSTRAINT `log_activity_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `parameter_tes`
--
ALTER TABLE `parameter_tes`
  ADD CONSTRAINT `parameter_tes_tes_id_foreign` FOREIGN KEY (`tes_id`) REFERENCES `jenis_tes` (`tes_id`) ON DELETE CASCADE;

--
-- Constraints for table `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `pasien_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_booking`
--
ALTER TABLE `riwayat_booking`
  ADD CONSTRAINT `riwayat_booking_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_booking_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `staf`
--
ALTER TABLE `staf`
  ADD CONSTRAINT `staf_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`cabang_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staf_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
