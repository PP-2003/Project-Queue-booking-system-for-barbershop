-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 11:45 AM
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
-- Database: `barber1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `services` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `queue_number` varchar(50) DEFAULT NULL,
  `slip_path` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `name`, `phone`, `booking_date`, `booking_time`, `services`, `created_at`, `queue_number`, `slip_path`, `status`) VALUES
(1, 'บอส', '0998988937', '2025-03-11', '11:30:00', 'ตัดผม', '2025-03-10 13:09:13', 'A096', 'uploads/S__16850954.jpg', 'cancelled'),
(2, 'กานต์', '0957916011', '2025-03-11', '13:00:00', 'ตัดผม, กันจร,กันหน้า,โกนหนวด', '2025-03-10 13:17:07', 'A598', 'uploads/74758.jpg', 'active'),
(3, 'สุวิช', '0998988937', '2025-03-11', '14:00:00', 'สระไดร์+เซตทรงผม', '2025-03-10 13:20:42', 'A863', NULL, 'cancelled'),
(4, 'วีโอเล็ต', '0809447101', '2025-03-11', '14:00:00', 'สระไดร์+เซตทรงผม, สระไดร์', '2025-03-10 14:04:08', 'A808', 'uploads/S__16637955.jpg', 'active'),
(5, 'ปาล์มมี่', '0856321499', '2025-03-11', '16:00:00', 'โกรกผม ดำ-น้ำตาลเข้ม, สระไดร์+เซตทรงผม', '2025-03-10 14:05:28', 'A485', 'uploads/S__16850954.jpg', 'cancelled'),
(6, 'แซม', '0856145222', '2025-03-11', '17:00:00', 'ตัดผม, กันจร,กันหน้า,โกนหนวด, แคะหู,ล้างหู', '2025-03-10 14:06:19', 'A176', 'uploads/74758.jpg', 'active'),
(7, 'เกมส์', '0682455566', '2025-03-11', '15:30:00', 'สระไดร์, ย้อมสีผม', '2025-03-10 14:08:48', 'A741', 'uploads/S__16637955.jpg', 'active'),
(8, 'จองกุก', '0859995016', '2025-06-15', '13:30:00', 'ตัดผม, สระไดร์+เซตทรงผม', '2025-06-14 09:19:49', 'A844', 'uploads/S__24403971.jpg', 'active'),
(9, 'ด', 'ด', '2025-06-16', '09:00:00', 'ไม่ได้เลือกบริการ', '2025-06-15 06:05:29', 'A434', NULL, 'cancelled'),
(10, 'กาย', '0982224567', '2025-07-09', '14:30:00', 'ตัดผม, กันจร,กันหน้า,โกนหนวด', '2025-07-08 02:31:21', 'A208', NULL, 'cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `role`) VALUES
(1, 'test01', 'kanpoorimat@gmail.com', '0957916011', '$2y$10$iE/G03EkXBGeF7goq5bgU.4KU73D3tHJeTLWn7daCOHEdOFKt/b0e', 'customer'),
(2, 'Palmy21', '664313113@mail.ac.th', '0838312931', '$2y$10$ycITXBYeHSNiwrNNm2E97.RuxPAFuNp8vXTECltRwkbEoXSiBtp6m', 'staff'),
(3, 'admin', 'admin@gmail.com', '0612456936', '$2y$10$QyfZMPUUT/KeL7DiwzFNL..dkDp/XCq0SHR.vCfdf2SeD8I20DwJm', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
