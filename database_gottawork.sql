-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2025 at 02:17 PM
-- Server version: 5.7.39
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
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `workspace` varchar(50) DEFAULT NULL,
  `desk_number` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `email`, `start_date`, `end_date`, `start_time`, `end_time`, `workspace`, `desk_number`, `created_at`) VALUES
(1, 'Carissa', 'carissa@gmail.com', '2025-06-01', '2025-06-01', '21:00:00', '22:00:00', 'Individual', '1', '2025-06-01 14:06:50');

-- --------------------------------------------------------

--
-- Table structure for table `harga`
--

CREATE TABLE `harga` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `price` varchar(50) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `harga`
--

INSERT INTO `harga` (`id`, `title`, `description`, `price`, `unit`) VALUES
(1, 'Meeting Room', 'Private space for meetings, presentations or joint discussions', 'Rp 35k', '/Hour'),
(2, 'Individual Desk', 'Individual desks to provide privacy and improve concentration', 'Rp 5k', '/Hour'),
(3, 'Group Desk', 'Flexible tables for group work to increase productivity', 'Rp 20k', '/Hour'),
(4, 'Private Office', 'Private office space for individuals or teams', 'Rp 300k', '/Week');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `reservation_code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `workspace` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `finish_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `reservation_code`, `name`, `workspace`, `date`, `start_time`, `finish_time`) VALUES
(1, 'priv3003202503150515carokt', 'Carissa Oktavia', 'Private Office', '2025-03-30', '03:15:00', '05:15:00'),
(2, 'meet3003202503150515rizana', 'Rizqi Ananda', 'Meeting Room', '2025-03-30', '07:30:00', '08:30:00'),
(3, 'stud3003202503150515ferrom', 'Fernando Ramadhani', 'Study Room', '2025-03-30', '14:45:00', '17:45:00'),
(4, 'grup3003202503150515orysur', 'Oryzo Surya', 'Group Desk', '2025-03-30', '20:00:00', '21:00:00'),
(5, 'indi3103202503150515upi000', 'Upin', 'Individual Desk', '2025-03-31', '07:00:00', '09:00:00'),
(6, 'priv3103202503150515ipi000', 'Ipin', 'Private Office', '2025-03-31', '13:30:00', '14:30:00'),
(7, 'priv3103202503150515kakros', 'Kak Ros', 'Private Office', '2025-03-31', '15:15:00', '19:15:00'),
(8, 'stud0104202503150515opa000', 'Opah', 'Study Room', '2025-04-01', '10:45:00', '16:45:00'),
(9, 'ind0104202503150515mai000', 'Mail', 'Individual Desk', '2025-04-01', '10:45:00', '11:45:00'),
(10, 'grup0104202503150515jar000', 'Jarjit', 'Group Desk', '2025-04-01', '11:30:00', '15:30:00'),
(11, 'priv0204202503150515meimei', 'Mei mei', 'Private Office', '2025-04-02', '01:45:00', '04:45:00'),
(12, 'meet0204202503150515sus000', 'Susanti', 'Meeting Room', '2025-04-02', '03:00:00', '05:00:00'),
(13, 'indi0204202503150515ehs000', 'Ehsan', 'Individual Desk', '2025-04-02', '08:30:00', '09:30:00'),
(14, 'stud020402503150515fiz000', 'Fizi', 'Study Room', '2025-04-02', '08:30:00', '11:30:00'),
(15, 'grup0204202503150515uncoht', 'Uncle Ah Tong', 'Group Desk', '2025-04-02', '13:00:00', '14:00:00'),
(16, 'indi0204202503150515uncmut', 'Uncle Muthu', 'Individual Desk', '2025-04-02', '19:15:00', '22:15:00'),
(17, 'meet0404202503150515abosal', 'Abang Saleh', 'Meeting Room', '2025-04-04', '11:00:00', '14:00:00'),
(18, 'indi0404202503150515cikbes', 'Cikgu Besar', 'Individual Desk', '2025-04-04', '11:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Customer','Manager','Staff') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@gottawork.com', 'Manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-05-15 15:08:39'),
(3, 'nando', 'nandovivo181@gmail.com', 'Customer', '$2y$10$l4E7Tv4KHG49l/Z53aCGB.QO6NUHjL/Nu1I.RD8s.ss1gFaMiUfBC', '2025-05-15 15:29:52'),
(5, 'rizqi', 'rizqi123@gmail.com', 'Staff', '$2y$10$Fcoag3oNuQfjtIxvcaqDp..D6H8ppdSzo/nqQlIHmXymimX1BoqJG', '2025-05-15 16:01:07'),
(7, 'Carissa', 'carissa@gmail.com', 'Customer', '$2y$10$4M3rBJgF74ZdN.TF3IAHDekzRr3bYPEPgBYUZEwSSoAwYYIMPhciu', '2025-05-22 16:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `location` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `duration_unit` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `name`, `description`, `location`, `price`, `duration_unit`, `image_path`) VALUES
(1, 'Meeting Room', 'Private space for meetings and presentations', 'Mall Boemi Kedaton', 35000, 'hour', 'assets/meetingroom.jpg'),
(2, 'Individual Desk', 'Individual desks to improve concentration', 'Lampung City Mall', 5000, 'hour', 'assets/individualdesk.jpeg'),
(3, 'Group Desk', 'Flexible tables for group work', 'Mall Boemi Kedaton', 20000, 'hour', 'assets/groupdesk.jpeg'),
(4, 'Private Office', 'Private office space for individuals or teams', 'Mall Boemi Kedaton', 300000, 'week', 'assets/privateoffice.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `harga`
--
ALTER TABLE `harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
