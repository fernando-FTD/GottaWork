-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 02:14 PM
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

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `email`, `user_id`, `start_date`, `end_date`, `start_time`, `end_time`, `workspace_type`, `workspace_name`, `location`, `image_path`, `desk_number`, `created_at`) VALUES
(48, 'Dexter', 'dexter@lab.com', 3, '2025-06-05', '2025-06-05', '18:00:00', '19:00:00', 'Private Office', 'Private Office', 'Mall Boemi Kedaton', 'assets/6852b898ddb91-individualdesk.jpeg', '5', '2025-06-05 10:59:00'),
(49, 'Jimmy Neutron', 'jimmy@brainblast.com', 3, '2025-06-06', '2025-06-06', '17:00:00', '18:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '10', '2025-06-06 09:59:00'),
(50, 'Pikachu', 'pikachu@pokemon.com', 3, '2025-06-07', '2025-06-07', '16:00:00', '17:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '8', '2025-06-07 08:59:00'),
(51, 'Ash Ketchum', 'ash@pokemon.com', 3, '2025-06-08', '2025-06-08', '15:00:00', '16:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '9', '2025-06-08 07:59:00'),
(52, 'Wanda', 'wanda@fairly.com', 3, '2025-06-08', '2025-06-08', '14:00:00', '15:00:00', 'Coworking Cafe', 'Coworking Cafe', 'Chandra swalayan metro pusat', 'assets/6852c210744ed-coworking-cafe.jpg', '3', '2025-06-08 06:59:00'),
(53, 'Cosmo', 'cosmo@fairly.com', 3, '2025-06-09', '2025-06-09', '13:00:00', '14:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '7', '2025-06-09 05:59:00'),
(54, 'Timmy Turner', 'timmy@fairly.com', 3, '2025-06-09', '2025-06-09', '11:00:00', '12:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '8', '2025-06-09 03:59:00'),
(55, 'Mr. Krabs', 'krabs@krustykrab.com', 3, '2025-06-10', '2025-06-10', '10:00:00', '11:00:00', 'Private Office', 'Private Office', 'Mall Boemi Kedaton', 'assets/6852b898ddb91-individualdesk.jpeg', '4', '2025-06-10 02:59:00'),
(56, 'Woody', 'woody@toystory.com', 3, '2025-06-10', '2025-06-10', '09:00:00', '10:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '6', '2025-06-10 01:59:00'),
(57, 'Buzz Lightyear', 'buzz@toystory.com', 3, '2025-06-11', '2025-06-11', '08:00:00', '09:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '7', '2025-06-11 00:59:00'),
(58, 'Anna', 'anna@arendelle.com', 3, '2025-06-11', '2025-06-11', '16:00:00', '17:00:00', 'Coworking Cafe', 'Coworking Cafe', 'Chandra swalayan metro pusat', 'assets/6852c210744ed-coworking-cafe.jpg', '2', '2025-06-11 08:59:00'),
(59, 'Elsa', 'elsa@arendelle.com', 3, '2025-06-12', '2025-06-12', '15:00:00', '16:00:00', 'Private Office', 'Private Office', 'Mall Boemi Kedaton', 'assets/6852b898ddb91-individualdesk.jpeg', '3', '2025-06-12 07:59:00'),
(60, 'Goofy', 'goofy@disney.com', 3, '2025-06-12', '2025-06-12', '14:00:00', '15:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '5', '2025-06-12 06:59:00'),
(61, 'Donald Duck', 'donald@disney.com', 3, '2025-06-13', '2025-06-13', '13:00:00', '14:00:00', 'Coworking Cafe', 'Coworking Cafe', 'Chandra swalayan metro pusat', 'assets/6852c210744ed-coworking-cafe.jpg', '1', '2025-06-13 05:59:00'),
(62, 'Mickey Mouse', 'mickey@disney.com', 3, '2025-06-13', '2025-06-13', '11:00:00', '12:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '6', '2025-06-13 03:59:00'),
(63, 'Shizuka Minamoto', 'shizuka@tokyo.jp', 3, '2025-06-14', '2025-06-14', '10:00:00', '11:00:00', 'Private Office', 'Private Office', 'Mall Boemi Kedaton', 'assets/6852b898ddb91-individualdesk.jpeg', '2', '2025-06-14 02:59:00'),
(64, 'Nobita Nobi', 'nobita@tokyo.jp', 3, '2025-06-14', '2025-06-14', '09:00:00', '10:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '4', '2025-06-14 01:59:00'),
(65, 'Doraemon', 'doraemon@tokyo.jp', 3, '2025-06-15', '2025-06-15', '08:00:00', '09:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '5', '2025-06-15 00:59:00'),
(66, 'Sasuke Uchiha', 'sasuke@uchiha.jp', 3, '2025-06-15', '2025-06-15', '16:00:00', '17:00:00', 'Private Office', 'Private Office', 'Mall Boemi Kedaton', 'assets/6852b898ddb91-individualdesk.jpeg', '1', '2025-06-15 08:59:00'),
(67, 'Naruto Uzumaki', 'naruto@konoha.jp', 3, '2025-06-16', '2025-06-16', '15:00:00', '16:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '3', '2025-06-16 07:59:00'),
(68, 'Jerry Mouse', 'jerry@mouse.com', 3, '2025-06-16', '2025-06-16', '14:00:00', '15:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '4', '2025-06-16 06:59:00'),
(69, 'Tom Cat', 'tom@cat.com', 3, '2025-06-17', '2025-06-17', '13:00:00', '14:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '2', '2025-06-17 05:59:00'),
(70, 'Squidward Tentacles', 'squidward@bikinibottom.com', 3, '2025-06-17', '2025-06-17', '11:00:00', '12:00:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '1', '2025-06-17 03:59:00'),
(71, 'Patrick Star', 'patrick@bikinibottom.com', 3, '2025-06-18', '2025-06-18', '10:00:00', '11:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '3', '2025-06-18 02:59:00'),
(72, 'SpongeBob SquarePants', 'spongebob@bikinibottom.com', 3, '2025-06-18', '2025-06-18', '09:00:00', '10:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '2', '2025-06-18 01:59:00'),
(73, 'Carissa', 'carissa@gmail.com', 3, '2025-06-19', '2025-06-19', '18:04:00', '19:04:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '1', '2025-06-19 11:05:11'),
(75, 'Oryza', 'ory@gmail.com', 3, '2025-06-19', '2025-06-19', '19:23:00', '20:23:00', 'Group Desk', 'Group Desk', 'Mall Boemi Kedaton', 'assets/6852bca27d661-groupdesk.jpeg', '8', '2025-06-19 11:24:04'),
(77, 'nando-chan', 'nandovivo181@gmail.com', 3, '2025-06-19', '2025-06-19', '21:00:00', '22:00:00', 'Individual Desk', 'Individual Desk', 'Lampung City Mall', 'assets/individualdesk.jpeg', '1', '2025-06-19 14:01:07');

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
(3, '2024-03-27', 'Gaji Karyawan Bulan Maret', 'Gaji', '65000000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-19 11:16:28'),
(5, '2024-03-25', 'Maintenance AC dan Cleaning Service', 'Maintenance', '3200000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(7, '2024-03-24', 'Biaya Internet dan Telepon', 'Utilitas', '1800000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(9, '2024-03-22', 'Pembelian Alat Tulis Kantor', 'Operasional', '850000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(11, '2024-03-20', 'Biaya Marketing Digital', 'Marketing', '5500000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:42:38'),
(13, '2024-03-18', 'Pembayaran Air PDAM', 'Utilitas', '650000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(15, '2024-03-15', 'Pembelian Peralatan IT', 'Operasional', '12000000.00', 'Selesai', '2025-06-02 14:33:24', '2025-06-02 14:33:24'),
(21, '2025-06-18', 'Claning Service', 'Maintenance', '300000.00', 'Selesai', '2025-06-18 16:56:58', '2025-06-18 16:56:58'),
(23, '2025-06-19', 'Cleaning Service', 'Maintenance', '200000.00', 'Selesai', '2025-06-19 13:39:05', '2025-06-19 13:39:05');

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
(9, '2024-03-16', 'Layanan Catering - Meeting PT Sejahtera', 'Layanan Tambahan', '3500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:24:34'),
(11, '2024-03-15', 'Perpanjangan Membership - Freelancer Group', 'Membership', '7500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(13, '2024-03-07', 'Sewa Ruang Meeting - PT Konsultasi Bisnis', 'Ruang Meeting', '1800000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-17 17:38:07'),
(15, '2024-03-04', 'Event Peluncuran Produk - Startup Fintech', 'Event Space', '12500000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(17, '2024-03-03', 'Membership Baru - PT Digital Kreatif', 'Membership', '18000000.00', 'Selesai', NULL, '2025-06-02 14:15:15', '2025-06-02 14:15:15'),
(39, '2025-06-18', 'Booking Individual Desk Meja 12 (2.0 jam)', 'Individual Desk', '10000.00', 'Selesai', 49, '2025-06-18 16:46:26', '2025-06-18 16:55:05'),
(63, '2025-06-19', 'Booking Individual Desk Meja 1 (1.0 jam)', 'Individual Desk', '5000.00', 'Selesai', 73, '2025-06-19 11:05:11', '2025-06-19 14:01:44'),
(65, '2025-06-19', 'Booking Private Office', 'Private Office', '600000.00', 'Selesai', NULL, '2025-06-19 11:10:19', '2025-06-19 11:10:54'),
(67, '2025-06-19', 'Booking Group Desk Meja 8 (1.0 jam)', 'Group Desk', '20000.00', 'Selesai', 75, '2025-06-19 11:24:04', '2025-06-19 14:01:52'),
(69, '2025-06-19', 'Booking Individual Desk Meja 1 (1.0 jam)', 'Individual Desk', '5000.00', 'Selesai', 77, '2025-06-19 14:01:07', '2025-06-19 14:02:04');

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
(1, 'Admin', 'admin@gottawork.com', 'Manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-15 15:08:39'),
(3, 'nando', 'nandovivo181@gmail.com', 'Customer', '$2y$10$l4E7Tv4KHG49l/Z53aCGB.QO6NUHjL/Nu1I.RD8s.ss1gFaMiUfBC', '2025-05-15 15:29:52'),
(5, 'rizqi ananda', 'rizqi123@gmail.com', 'Staff', '$2y$10$Fcoag3oNuQfjtIxvcaqDp..D6H8ppdSzo/nqQlIHmXymimX1BoqJG', '2025-05-15 16:01:07'),
(7, 'Carissa', 'carissa@gmail.com', 'Customer', '$2y$10$4M3rBJgF74ZdN.TF3IAHDekzRr3bYPEPgBYUZEwSSoAwYYIMPhciu', '2025-05-22 16:08:44'),
(9, 'nando2', 'fernandoramadhani0611@gmail.com', 'Customer', '$2y$10$KcZA4Iq/gNMj.o0/zV.LUuDPd0Mr5ifT6NQOEaOMhp/8DWja4gKBa', '2025-06-17 08:46:21'),
(11, 'ory', 'ory@gmail.com', 'Customer', '$2y$10$/jL2NaM5QOwXbM.4FmqYeOdUPh7YgPBfvWR1Z7HdzpbCt/cdv.eIm', '2025-06-19 10:40:40'),
(13, 'ahuy', 'ahuy@gmail.com', 'Customer', '$2y$10$1F8W6klai71dL2iVqyzUZeG9QiiFW8q3Hb6CRKJyToLG168sEbr3O', '2025-06-19 10:47:00');

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
(1, 'Meeting Room', 'Private space for meetings and presentations', 'Mall Boemi Kedaton', 'Meeting Room', 12, '[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]', 35000, 'hour', 'assets/6853e7e8ea5e9-meetingroom.jpg', 'Aktif'),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
