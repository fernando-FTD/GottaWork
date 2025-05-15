-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 15, 2025 at 02:15 PM
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
(1, 'Meeting Room', 'Ruang pribadi untuk rapat, presentasi atau diskusi bersama', 'Rp 35k', '/Hour'),
(2, 'Individual Desk', 'Meja individu untuk memberikan privasi dan meningkatkan konsentrasi', 'Rp 5k', '/Hour'),
(3, 'Group Desk', 'Meja fleksibel untuk kerja kelompok untuk meningkatkan produktivitas', 'Rp 20k', '/Hour'),
(4, 'Private Office', 'Ruang kantor pribadi untuk individu atau tim', 'Rp 300k', '/Week');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `reservation_code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `workspace` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `finish_time` time DEFAULT NULL,
  `user_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `reservation_code`, `name`, `workspace`, `date`, `start_time`, `finish_time`, `user_status`) VALUES
(1, 'RES001', 'John Doe', 'Private Office', '2025-03-30', '09:00:00', '11:00:00', 'Member'),
(2, 'RES002', 'Jane Smith', 'Meeting Room', '2025-03-31', '10:00:00', '12:00:00', 'Non-member'),
(3, 'RES003', 'Alice Brown', 'Study Room', '2025-04-01', '13:00:00', '15:00:00', 'Member'),
(4, 'RES004', 'Bob White', 'Group Desk', '2025-04-02', '08:30:00', '10:30:00', 'Non-member'),
(5, 'RES005', 'Charlie Black', 'Individual Desk', '2025-04-03', '14:00:00', '16:00:00', 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
