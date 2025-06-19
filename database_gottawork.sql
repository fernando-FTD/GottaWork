-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 04:39 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_gottawork`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `workspace_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `workspace_name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `desk_number` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `kategori` enum('Operasional','Maintenance','Utilitas','Marketing','Gaji','Lainnya') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('Selesai','Tertunda') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `tanggal`, `deskripsi`, `kategori`, `jumlah`, `status`, `created_at`, `updated_at`) VALUES
(1, '2024-03-28', 'Pembayaran Listrik Bulanan', 'Utilitas', '2500000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(3, '2024-03-27', 'Gaji Karyawan Bulan Maret', 'Gaji', '45000000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(5, '2024-03-25', 'Maintenance AC dan Cleaning Service', 'Maintenance', '3200000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(7, '2024-03-24', 'Biaya Internet dan Telepon', 'Utilitas', '1800000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(9, '2024-03-22', 'Pembelian Alat Tulis Kantor', 'Operasional', '850000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(11, '2024-03-20', 'Biaya Marketing Digital', 'Marketing', '5500000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:42:38'),
(13, '2024-03-18', 'Pembayaran Air PDAM', 'Utilitas', '650000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(15, '2024-03-15', 'Pembelian Peralatan IT', 'Operasional', '12000000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(17, '2024-03-12', 'Biaya Keamanan dan Parkir', 'Operasional', '2200000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(21, '2025-06-18', 'Claning Service', 'Maintenance', '300000.00', 'Selesai', '2025-06-18 16:56:58', '2025-06-18 16:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `price` varchar(50) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `title`, `description`, `price`, `unit`) VALUES
(1, 'Meeting Room', 'Private space for meetings, presentations or joint discussions', 'Rp 35k', '/Hour'),
(2, 'Individual Desk', 'Individual desks to provide privacy and improve concentration', 'Rp 5k', '/Hour'),
(3, 'Group Desk', 'Flexible tables for group work to increase productivity', 'Rp 100k', '/Hour'),
(4, 'Private Office', 'Private office space for individuals or teams', 'Rp 300k', '/Week');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('Selesai','Tertunda') NOT NULL,
  `booking_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `tanggal`, `deskripsi`, `kategori`, `jumlah`, `status`, `booking_id`, `created_at`, `updated_at`) VALUES
(1, '2024-03-28', 'Perpanjangan Membership - PT Maju Bersama', 'Membership', '15000000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(3, '2024-03-24', 'Sewa Ruang Meeting - Startup Inovasi', 'Ruang Meeting', '2700000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(5, '2024-03-22', 'Event Workshop Digital Marketing', 'Event Space', '8750000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(7, '2024-03-20', 'Membership Baru - CV Nusantara Maju', 'Membership', '12000000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(9, '2024-03-16', 'Layanan Catering - Meeting PT Sejahtera', 'Layanan Tambahan', '3500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:24:34'),
(11, '2024-03-15', 'Perpanjangan Membership - Freelancer Group', 'Membership', '7500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(13, '2024-03-07', 'Sewa Ruang Meeting - PT Konsultasi Bisnis', 'Ruang Meeting', '1800000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-17 17:38:07'),
(15, '2024-03-04', 'Event Peluncuran Produk - Startup Fintech', 'Event Space', '12500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(17, '2024-03-03', 'Membership Baru - PT Digital Kreatif', 'Membership', '18000000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(39, '2025-06-18', 'Booking Individual Desk Meja 12 (2.0 jam)', 'Individual Desk', '10000.00', 'Selesai', 49, '2025-06-18 16:46:26', '2025-06-18 16:55:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Customer','Manager','Staff') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'Admin Telah Tiba', 'admin@gottawork.com', 'Manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-15 15:08:39'),
(3, 'manusia paling tampan', 'nandovivo181@gmail.com', 'Customer', '$2y$10$l4E7Tv4KHG49l/Z53aCGB.QO6NUHjL/Nu1I.RD8s.ss1gFaMiUfBC', '2025-05-15 15:29:52'),
(5, 'rizqi-chan', 'rizqi123@gmail.com', 'Staff', '$2y$10$Fcoag3oNuQfjtIxvcaqDp..D6H8ppdSzo/nqQlIHmXymimX1BoqJG', '2025-05-15 16:01:07'),
(7, 'Carissa', 'carissa@gmail.com', 'Customer', '$2y$10$4M3rBJgF74ZdN.TF3IAHDekzRr3bYPEPgBYUZEwSSoAwYYIMPhciu', '2025-05-22 16:08:44'),
(9, 'nando2', 'fernandoramadhani0611@gmail.com', 'Customer', '$2y$10$KcZA4Iq/gNMj.o0/zV.LUuDPd0Mr5ifT6NQOEaOMhp/8DWja4gKBa', '2025-06-17 08:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `location` varchar(100) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `fasilitas` json DEFAULT NULL,
  `price` int DEFAULT NULL,
  `duration_unit` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `name`, `description`, `location`, `tipe`, `kapasitas`, `fasilitas`, `price`, `duration_unit`, `image_path`, `status`) VALUES
(1, 'Meeting Room', 'Private space for meetings and presentations', 'Mall Boemi Kedaton', NULL, NULL, NULL, 35000, 'hour', 'assets/meetingroom.jpg', 'Aktif'),
(2, 'Individual Desk', 'Individual desks to improve concentration', 'Lampung City Mall', 'Individual Desk', 1, '[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\"]', 5000, 'hour', 'assets/individualdesk.jpeg', 'Aktif'),
(3, 'Group Desk', 'Flexible tables for group work', 'Mall Boemi Kedaton', 'Group Desk', 8, '[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]', 20000, 'hour', 'assets/6852bca27d661-groupdesk.jpeg', 'Aktif'),
(4, 'Private Office', 'Private office space for individuals or teams', 'Mall Boemi Kedaton', 'Private Office', 1, '[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]', 300000, 'week', 'assets/6852b898ddb91-individualdesk.jpeg', 'Tidak Aktif'),
(7, 'Coworking Cafe', 'tempat kerja yang bernuansa cafe yang nyaman dan tenang', 'Chandra swalayan metro pusat', 'Group Desk', 24, '[\"WiFi\", \"AC\", \"Whiteboard\", \"Coffee/Tea\", \"Stopkontak\"]', 25000, 'hour', 'assets/6852c210744ed-coworking-cafe.jpg', 'Maintenance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id_index` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
