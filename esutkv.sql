-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2026 at 01:56 PM
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
-- Database: `esutkv`
--

-- --------------------------------------------------------

--
-- Table structure for table `acaras`
--

CREATE TABLE `acaras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_acara` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acaras`
--

INSERT INTO `acaras` (`id`, `nama_acara`, `kategori`, `created_at`, `updated_at`) VALUES
(1, '4X100', 'LARIAN', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(2, '1X100', 'LARIAN', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(3, '1X400', 'LARIAN', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(4, '4X400', 'LARIAN', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(5, 'LONTAR PELURU', 'PADANG', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(6, 'REJAM LEMBING', 'PADANG', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(7, 'LOMPAT TINGGI', 'PADANG', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(8, 'LOMPAT JAUH', 'PADANG', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(9, 'LEMPAR CAKERA', 'PADANG', '2026-11-04 23:15:20', '2026-11-04 23:15:20'),
(10, 'TARIK TALI', 'ACARA KHAS', '2026-11-04 23:15:20', '2026-11-04 23:15:20');

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
-- Table structure for table `keputusans`
--

CREATE TABLE `keputusans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `acara_id` bigint(20) UNSIGNED NOT NULL,
  `peserta_id` bigint(20) UNSIGNED NOT NULL,
  `kedudukan` varchar(255) NOT NULL,
  `mata` int(11) NOT NULL,
  `jenis_keputusan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keputusan_acaras`
--

CREATE TABLE `keputusan_acaras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `acara_id` bigint(20) UNSIGNED NOT NULL,
  `peserta_id` bigint(20) UNSIGNED NOT NULL,
  `rumah_sukan_id` bigint(20) UNSIGNED NOT NULL,
  `pingat` varchar(255) NOT NULL,
  `mata` int(10) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2026_11_01_061923_rumah_sukans', 1),
(5, '2026_11_01_062157_acaras', 1),
(6, '2026_11_01_062304_pesertas', 1),
(7, '2026_11_01_062346_keputusans', 1),
(8, '2026_11_05_070000_add_warna_to_rumah_sukans_table', 2),
(9, '2026_11_05_070100_add_jantina_to_pesertas_table', 3),
(10, '2026_11_05_080000_add_roles_to_users_table', 4),
(11, '2026_11_05_080100_create_keputusan_acaras_table', 4);

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
-- Table structure for table `pesertas`
--

CREATE TABLE `pesertas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_peserta` varchar(255) NOT NULL,
  `rumah_sukan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jantina` enum('L','P') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesertas`
--

INSERT INTO `pesertas` (`id`, `nama_peserta`, `rumah_sukan_id`, `created_at`, `updated_at`, `jantina`) VALUES
(2, 'SITI BINTI ALI', 2, '2026-11-04 23:37:41', '2026-11-04 23:37:41', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `rumah_sukans`
--

CREATE TABLE `rumah_sukans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_rumah` varchar(255) NOT NULL,
  `jumlah_mata` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warna` varchar(255) NOT NULL DEFAULT '#6955e8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rumah_sukans`
--

INSERT INTO `rumah_sukans` (`id`, `nama_rumah`, `jumlah_mata`, `created_at`, `updated_at`, `warna`) VALUES
(1, 'MERAH', 0, '2026-11-04 23:07:11', '2026-11-04 23:07:11', '#e34f61'),
(2, 'BIRU', 0, '2026-11-04 23:07:11', '2026-11-04 23:07:11', '#397bd8'),
(3, 'KUNING', 0, '2026-11-04 23:07:11', '2026-11-04 23:07:11', '#dfa912'),
(4, 'HIJAU', 0, '2026-11-04 23:07:11', '2026-11-04 23:07:11', '#159b69');

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
('eHUT5uYBT6U543jZPybQEttK7eNvNh8ndJtLXkpb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.144.1 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0VINmR2NWtDTEdCUXBtTXA2NTRYd3BGN1NKV2JNYm4wbEdqWll6VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1793860866),
('mXo3S7cnXbXlrnchHuWaZdWoJGxZbrfBHchyg2tF', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.144.1 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ1U1c015UUI4c1loanhqSFhOSXpsWkxYbkZXWElxeVlVb2lBN0gzRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9sb2NhbGhvc3QvZXN1dGt2L3B1YmxpYy9hZG1pbi9rZXB1dHVzYW4iO3M6NToicm91dGUiO3M6MTU6ImtlcHV0dXNhbi5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImJjOTc1NmM4ZjAzZmJmODY4YzE5OGM0OTExMTc4YjZkMGUxNWI5NzhmNjc4YjFhYzE2YmIwYjIwNWNlNmVhYjgiO30=', 1793866407);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `rumah_sukan_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `rumah_sukan_id`) VALUES
(6, 'Admin', 'admin@gmail.com', NULL, '$2y$12$N9YtZl1NnduSXcKYHHXFm.5EihZ.Nh7rzHUCR73UrNMpm8osawZk6', NULL, '2026-10-31 22:48:30', '2026-10-31 22:48:30', 'admin', NULL),
(7, 'Guru MERAH', 'alpha@gmail.com', NULL, '$2y$12$IGu2bVK0vpOCxryr.Y57aeQKFmvaUbw1GyNmRCmQHiN0uWVTH1eA2', NULL, '2026-11-05 00:04:43', '2026-11-05 00:04:43', 'guru', 1),
(8, 'Guru BIRU', 'beta@gmail.com', NULL, '$2y$12$xe0/sfzD8iPJBAYlT/1s2eVhw8C3kVUmeBof3y2qn2jdUVT4KyWCq', NULL, '2026-11-05 00:04:43', '2026-11-05 00:04:43', 'guru', 2),
(9, 'Guru KUNING', 'sigma@gmail.com', NULL, '$2y$12$PtZGwOFzKcIUF17JdRIBTOzD0/5Ysq.G401fiPUAw49.zx8iOunre', NULL, '2026-11-05 00:04:43', '2026-11-05 00:04:43', 'guru', 3),
(10, 'Guru HIJAU', 'gamma@gmail.com', NULL, '$2y$12$u0PYVymYR23jn61dNWi1ZuKGhbYGM5bcSmPhcxBkG32A.faulV9sK', NULL, '2026-11-05 00:04:43', '2026-11-05 00:04:43', 'guru', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acaras`
--
ALTER TABLE `acaras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `keputusans`
--
ALTER TABLE `keputusans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keputusans_acara_id_foreign` (`acara_id`),
  ADD KEY `keputusans_peserta_id_foreign` (`peserta_id`);

--
-- Indexes for table `keputusan_acaras`
--
ALTER TABLE `keputusan_acaras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keputusan_acaras_acara_id_foreign` (`acara_id`),
  ADD KEY `keputusan_acaras_peserta_id_foreign` (`peserta_id`),
  ADD KEY `keputusan_acaras_rumah_sukan_id_foreign` (`rumah_sukan_id`),
  ADD KEY `keputusan_acaras_submitted_by_foreign` (`submitted_by`),
  ADD KEY `keputusan_acaras_reviewed_by_foreign` (`reviewed_by`);

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
-- Indexes for table `pesertas`
--
ALTER TABLE `pesertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesertas_rumah_sukan_id_foreign` (`rumah_sukan_id`);

--
-- Indexes for table `rumah_sukans`
--
ALTER TABLE `rumah_sukans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_rumah_sukan_id_foreign` (`rumah_sukan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acaras`
--
ALTER TABLE `acaras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keputusans`
--
ALTER TABLE `keputusans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keputusan_acaras`
--
ALTER TABLE `keputusan_acaras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pesertas`
--
ALTER TABLE `pesertas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rumah_sukans`
--
ALTER TABLE `rumah_sukans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keputusans`
--
ALTER TABLE `keputusans`
  ADD CONSTRAINT `keputusans_acara_id_foreign` FOREIGN KEY (`acara_id`) REFERENCES `acaras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keputusans_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `pesertas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `keputusan_acaras`
--
ALTER TABLE `keputusan_acaras`
  ADD CONSTRAINT `keputusan_acaras_acara_id_foreign` FOREIGN KEY (`acara_id`) REFERENCES `acaras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keputusan_acaras_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `pesertas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keputusan_acaras_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `keputusan_acaras_rumah_sukan_id_foreign` FOREIGN KEY (`rumah_sukan_id`) REFERENCES `rumah_sukans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keputusan_acaras_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesertas`
--
ALTER TABLE `pesertas`
  ADD CONSTRAINT `pesertas_rumah_sukan_id_foreign` FOREIGN KEY (`rumah_sukan_id`) REFERENCES `rumah_sukans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rumah_sukan_id_foreign` FOREIGN KEY (`rumah_sukan_id`) REFERENCES `rumah_sukans` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
