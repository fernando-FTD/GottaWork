-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: database_gottawork
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (48,'Dexter','dexter@lab.com',3,'2025-06-05','2025-06-05','18:00:00','19:00:00','Private Office','Private Office','Mall Boemi Kedaton','assets/6852b898ddb91-individualdesk.jpeg','5','2025-06-05 10:59:00'),(49,'Jimmy Neutron','jimmy@brainblast.com',3,'2025-06-06','2025-06-06','17:00:00','18:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','10','2025-06-06 09:59:00'),(50,'Pikachu','pikachu@pokemon.com',3,'2025-06-07','2025-06-07','16:00:00','17:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','8','2025-06-07 08:59:00'),(51,'Ash Ketchum','ash@pokemon.com',3,'2025-06-08','2025-06-08','15:00:00','16:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','9','2025-06-08 07:59:00'),(52,'Wanda','wanda@fairly.com',3,'2025-06-08','2025-06-08','14:00:00','15:00:00','Coworking Cafe','Coworking Cafe','Chandra swalayan metro pusat','assets/6852c210744ed-coworking-cafe.jpg','3','2025-06-08 06:59:00'),(53,'Cosmo','cosmo@fairly.com',3,'2025-06-09','2025-06-09','13:00:00','14:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','7','2025-06-09 05:59:00'),(54,'Timmy Turner','timmy@fairly.com',3,'2025-06-09','2025-06-09','11:00:00','12:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','8','2025-06-09 03:59:00'),(55,'Mr. Krabs','krabs@krustykrab.com',3,'2025-06-10','2025-06-10','10:00:00','11:00:00','Private Office','Private Office','Mall Boemi Kedaton','assets/6852b898ddb91-individualdesk.jpeg','4','2025-06-10 02:59:00'),(56,'Woody','woody@toystory.com',3,'2025-06-10','2025-06-10','09:00:00','10:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','6','2025-06-10 01:59:00'),(57,'Buzz Lightyear','buzz@toystory.com',3,'2025-06-11','2025-06-11','08:00:00','09:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','7','2025-06-11 00:59:00'),(58,'Anna','anna@arendelle.com',3,'2025-06-11','2025-06-11','16:00:00','17:00:00','Coworking Cafe','Coworking Cafe','Chandra swalayan metro pusat','assets/6852c210744ed-coworking-cafe.jpg','2','2025-06-11 08:59:00'),(59,'Elsa','elsa@arendelle.com',3,'2025-06-12','2025-06-12','15:00:00','16:00:00','Private Office','Private Office','Mall Boemi Kedaton','assets/6852b898ddb91-individualdesk.jpeg','3','2025-06-12 07:59:00'),(60,'Goofy','goofy@disney.com',3,'2025-06-12','2025-06-12','14:00:00','15:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','5','2025-06-12 06:59:00'),(61,'Donald Duck','donald@disney.com',3,'2025-06-13','2025-06-13','13:00:00','14:00:00','Coworking Cafe','Coworking Cafe','Chandra swalayan metro pusat','assets/6852c210744ed-coworking-cafe.jpg','1','2025-06-13 05:59:00'),(62,'Mickey Mouse','mickey@disney.com',3,'2025-06-13','2025-06-13','11:00:00','12:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','6','2025-06-13 03:59:00'),(63,'Shizuka Minamoto','shizuka@tokyo.jp',3,'2025-06-14','2025-06-14','10:00:00','11:00:00','Private Office','Private Office','Mall Boemi Kedaton','assets/6852b898ddb91-individualdesk.jpeg','2','2025-06-14 02:59:00'),(64,'Nobita Nobi','nobita@tokyo.jp',3,'2025-06-14','2025-06-14','09:00:00','10:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','4','2025-06-14 01:59:00'),(65,'Doraemon','doraemon@tokyo.jp',3,'2025-06-15','2025-06-15','08:00:00','09:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','5','2025-06-15 00:59:00'),(66,'Sasuke Uchiha','sasuke@uchiha.jp',3,'2025-06-15','2025-06-15','16:00:00','17:00:00','Private Office','Private Office','Mall Boemi Kedaton','assets/6852b898ddb91-individualdesk.jpeg','1','2025-06-15 08:59:00'),(67,'Naruto Uzumaki','naruto@konoha.jp',3,'2025-06-16','2025-06-16','15:00:00','16:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','3','2025-06-16 07:59:00'),(68,'Jerry Mouse','jerry@mouse.com',3,'2025-06-16','2025-06-16','14:00:00','15:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','4','2025-06-16 06:59:00'),(69,'Tom Cat','tom@cat.com',3,'2025-06-17','2025-06-17','13:00:00','14:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','2','2025-06-17 05:59:00'),(70,'Squidward Tentacles','squidward@bikinibottom.com',3,'2025-06-17','2025-06-17','11:00:00','12:00:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','1','2025-06-17 03:59:00'),(71,'Patrick Star','patrick@bikinibottom.com',3,'2025-06-18','2025-06-18','10:00:00','11:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','3','2025-06-18 02:59:00'),(72,'SpongeBob SquarePants','spongebob@bikinibottom.com',3,'2025-06-18','2025-06-18','09:00:00','10:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','2','2025-06-18 01:59:00'),(73,'Carissa','carissa@gmail.com',3,'2025-06-19','2025-06-19','18:04:00','19:04:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','1','2025-06-19 11:05:11'),(75,'Oryza','ory@gmail.com',3,'2025-06-19','2025-06-19','19:23:00','20:23:00','Group Desk','Group Desk','Mall Boemi Kedaton','assets/6852bca27d661-groupdesk.jpeg','8','2025-06-19 11:24:04'),(77,'nando-chan','nandovivo181@gmail.com',3,'2025-06-19','2025-06-19','21:00:00','22:00:00','Individual Desk','Individual Desk','Lampung City Mall','assets/individualdesk.jpeg','1','2025-06-19 14:01:07');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `kategori` enum('Operasional','Maintenance','Utilitas','Marketing','Gaji','Lainnya') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('Selesai','Tertunda') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'2024-03-28','Pembayaran Listrik Bulanan','Utilitas',2500000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(3,'2024-03-27','Gaji Karyawan Bulan Maret','Gaji',65000000.00,'Selesai','2025-06-02 14:33:24','2025-06-19 11:16:28'),(5,'2024-03-25','Maintenance AC dan Cleaning Service','Maintenance',3200000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(7,'2024-03-24','Biaya Internet dan Telepon','Utilitas',1800000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(9,'2024-03-22','Pembelian Alat Tulis Kantor','Operasional',850000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(11,'2024-03-20','Biaya Marketing Digital','Marketing',5500000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:42:38'),(13,'2024-03-18','Pembayaran Air PDAM','Utilitas',650000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(15,'2024-03-15','Pembelian Peralatan IT','Operasional',12000000.00,'Selesai','2025-06-02 14:33:24','2025-06-02 14:33:24'),(21,'2025-06-18','Claning Service','Maintenance',300000.00,'Selesai','2025-06-18 16:56:58','2025-06-18 16:56:58'),(23,'2025-06-19','Cleaning Service','Maintenance',200000.00,'Selesai','2025-06-19 13:39:05','2025-06-19 13:39:05');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('Selesai','Tertunda') NOT NULL,
  `booking_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id_index` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'2024-03-28','Perpanjangan Membership - PT Maju Bersama','Membership',15000000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(3,'2024-03-24','Sewa Ruang Meeting - Startup Inovasi','Ruang Meeting',2700000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(5,'2024-03-22','Event Workshop Digital Marketing','Event Space',8750000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(9,'2024-03-16','Layanan Catering - Meeting PT Sejahtera','Layanan Tambahan',3500000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:24:34'),(11,'2024-03-15','Perpanjangan Membership - Freelancer Group','Membership',7500000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(13,'2024-03-07','Sewa Ruang Meeting - PT Konsultasi Bisnis','Ruang Meeting',1800000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-17 17:38:07'),(15,'2024-03-04','Event Peluncuran Produk - Startup Fintech','Event Space',12500000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(17,'2024-03-03','Membership Baru - PT Digital Kreatif','Membership',18000000.00,'Selesai',NULL,'2025-06-02 14:15:15','2025-06-02 14:15:15'),(39,'2025-06-18','Booking Individual Desk Meja 12 (2.0 jam)','Individual Desk',10000.00,'Selesai',49,'2025-06-18 16:46:26','2025-06-18 16:55:05'),(63,'2025-06-19','Booking Individual Desk Meja 1 (1.0 jam)','Individual Desk',5000.00,'Selesai',73,'2025-06-19 11:05:11','2025-06-19 14:01:44'),(65,'2025-06-19','Booking Private Office','Private Office',600000.00,'Selesai',NULL,'2025-06-19 11:10:19','2025-06-19 11:10:54'),(67,'2025-06-19','Booking Group Desk Meja 8 (1.0 jam)','Group Desk',20000.00,'Selesai',75,'2025-06-19 11:24:04','2025-06-19 14:01:52'),(69,'2025-06-19','Booking Individual Desk Meja 1 (1.0 jam)','Individual Desk',5000.00,'Selesai',77,'2025-06-19 14:01:07','2025-06-19 14:02:04');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Customer','Manager','Staff') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Manager oryza','admin@gottawork.com','Manager','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2025-05-15 15:08:39'),(3,'nando','nandovivo181@gmail.com','Customer','$2y$10$l4E7Tv4KHG49l/Z53aCGB.QO6NUHjL/Nu1I.RD8s.ss1gFaMiUfBC','2025-05-15 15:29:52'),(5,'rizqi ananda','rizqi123@gmail.com','Staff','$2y$10$Fcoag3oNuQfjtIxvcaqDp..D6H8ppdSzo/nqQlIHmXymimX1BoqJG','2025-05-15 16:01:07'),(7,'Carissa','carissa@gmail.com','Customer','$2y$10$4M3rBJgF74ZdN.TF3IAHDekzRr3bYPEPgBYUZEwSSoAwYYIMPhciu','2025-05-22 16:08:44'),(9,'nando2','fernandoramadhani0611@gmail.com','Customer','$2y$10$KcZA4Iq/gNMj.o0/zV.LUuDPd0Mr5ifT6NQOEaOMhp/8DWja4gKBa','2025-06-17 08:46:21'),(11,'ory','ory@gmail.com','Customer','$2y$10$/jL2NaM5QOwXbM.4FmqYeOdUPh7YgPBfvWR1Z7HdzpbCt/cdv.eIm','2025-06-19 10:40:40'),(13,'ahuy','ahuy@gmail.com','Customer','$2y$10$1F8W6klai71dL2iVqyzUZeG9QiiFW8q3Hb6CRKJyToLG168sEbr3O','2025-06-19 10:47:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workspaces`
--

DROP TABLE IF EXISTS `workspaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workspaces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `location` varchar(100) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `kapasitas` int DEFAULT NULL,
  `fasilitas` json DEFAULT NULL,
  `price` int DEFAULT NULL,
  `duration_unit` varchar(10) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspaces`
--

LOCK TABLES `workspaces` WRITE;
/*!40000 ALTER TABLE `workspaces` DISABLE KEYS */;
INSERT INTO `workspaces` VALUES (1,'Meeting Room','Private space for meetings and presentations','Mall Boemi Kedaton','Meeting Room',12,'[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]',35000,'hour','assets/6853e7e8ea5e9-meetingroom.jpg','Aktif'),(2,'Individual Desk','Individual desks to improve concentration','Lampung City Mall','Individual Desk',1,'[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\"]',5000,'hour','assets/individualdesk.jpeg','Aktif'),(3,'Group Desk','Flexible tables for group work','Mall Boemi Kedaton','Group Desk',8,'[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]',20000,'hour','assets/6852bca27d661-groupdesk.jpeg','Aktif'),(4,'Private Office','Private office space for individuals or teams','Mall Boemi Kedaton','Private Office',1,'[\"WiFi\", \"AC\", \"Proyektor\", \"Whiteboard\", \"Printer\", \"Stopkontak\"]',300000,'week','assets/6852b898ddb91-individualdesk.jpeg','Tidak Aktif'),(7,'Coworking Cafe','tempat kerja yang bernuansa cafe yang nyaman dan tenang','Chandra swalayan metro pusat','Group Desk',24,'[\"WiFi\", \"AC\", \"Whiteboard\", \"Coffee/Tea\", \"Stopkontak\"]',25000,'hour','assets/6852c210744ed-coworking-cafe.jpg','Maintenance');
/*!40000 ALTER TABLE `workspaces` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-20 12:21:46
