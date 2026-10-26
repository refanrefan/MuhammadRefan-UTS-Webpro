-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2026 at 05:01 PM
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
-- Database: `db5e`
--

-- --------------------------------------------------------

--
-- Table structure for table `nilaimahasiswa`
--

CREATE TABLE `nilaimahasiswa` (
  `nim` varchar(10) NOT NULL,
  `namalengkap` varchar(100) NOT NULL,
  `nilaiakhir` decimal(5,2) NOT NULL,
  `nilaihuruf` varchar(1) NOT NULL,
  `predikat` varchar(30) NOT NULL,
  `entry_date` datetime DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilaimahasiswa`
--

INSERT INTO `nilaimahasiswa` (`nim`, `namalengkap`, `nilaiakhir`, `nilaihuruf`, `predikat`, `entry_date`, `modified_at`, `foto`) VALUES
('3202416004', 'Fahri Cilok', 59.30, 'D', 'Kurang Memuaskan', '2026-10-23 21:47:12', '2026-10-23 22:02:15', '3202416004_1792767570.jpg'),
('3202416021', 'Dadang Korneto', 78.60, 'B', 'Memuaskan', '2026-10-23 21:44:04', '2026-10-23 22:01:40', '3202416021_1792767700.jpg'),
('3202416030', 'Sesuai Standar', 60.00, 'C', 'Cukup Memuaskan', '2026-10-23 21:44:34', '2026-10-23 22:02:25', '3202416030_1792767473.jpg'),
('3202416037', 'Saputro Saputro', 80.52, 'A', 'Sangat Memuaskan', '2026-10-23 21:40:02', '2026-10-23 22:07:28', '3202416037_1792768048.jpg'),
('3202416043', 'Dudul', 25.00, 'E', 'Tidak Lulus', '2026-10-23 21:54:47', '2026-10-23 21:54:47', '3202416043_1792767287.jpg'),
('3202416050', 'Dwi Artanto', 37.00, 'E', 'Tidak Lulus', '2026-10-23 21:53:48', '2026-10-23 21:55:14', '3202416050_1792767314.jpg'),
('3202416070', 'Sucipto', 38.00, 'E', 'Tidak Lulus', '2026-10-23 21:52:53', '2026-10-23 21:56:17', '3202416070_1792767377.jpg'),
('3202416080', 'Muhammad Refan', 90.00, 'A', 'Sangat Memuaskan', '2026-10-21 22:48:55', '2026-10-21 23:01:05', '3202416080_1792598465.jpg'),
('3202416087', 'Pisang Molen', 50.00, 'D', 'Kurang Memuaskan', '2026-10-23 21:51:00', '2026-10-23 21:59:51', '3202416087_1792767591.jpg'),
('3202416098', 'Dan Yap', 80.00, 'A', 'Sangat Memuaskan', '2026-10-23 21:42:58', '2026-10-23 22:40:09', '3202416098_1792768407.jpg'),
('3202416100', 'Aku Gue', 79.50, 'B', 'Memuaskan', '2026-10-23 21:43:36', '2026-10-23 22:17:06', '3202416100_1792768626.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int(6) UNSIGNED NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `uploaded_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id_photo`, `file_name`, `uploaded_date`) VALUES
(1, 'img/sk01revisi.jpg', '2026-10-21 14:31:55'),
(2, 'img/SK03revisi.jpg', '2026-10-21 14:33:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nilaimahasiswa`
--
ALTER TABLE `nilaimahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
