-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 05:37 AM
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
-- Database: `traffic_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `fine_master`
--

CREATE TABLE `fine_master` (
  `violation_type` varchar(50) NOT NULL,
  `fine_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fine_master`
--

INSERT INTO `fine_master` (`violation_type`, `fine_amount`) VALUES
('Over Speed', 1000),
('Signal Jump', 1500),
('Wrong Lane', 800);

-- --------------------------------------------------------

--
-- Table structure for table `traffic_signal`
--

CREATE TABLE `traffic_signal` (
  `signal_id` int(11) NOT NULL,
  `red_time` int(11) NOT NULL,
  `yellow_time` int(11) NOT NULL,
  `green_time` int(11) NOT NULL,
  `mode` varchar(20) DEFAULT 'auto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traffic_signal`
--

INSERT INTO `traffic_signal` (`signal_id`, `red_time`, `yellow_time`, `green_time`, `mode`) VALUES
(1, 60, 5, 45, 'auto');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$HmKDeRJj3a8.7G8IiYt6rOV1iXRgP6pe8GR6j9aXW7ZlZMWbGLsUi', 'admin'),
(2, 'officer1', '$2y$10$etBZRGltuLLrPeWbEbvy5.LT9S/XzUjoYXPnuNctbnwXPA1Eh8eem', 'officer');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(30) DEFAULT NULL,
  `lane_no` int(11) DEFAULT NULL,
  `entry_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `vehicle_number`, `vehicle_type`, `lane_no`, `entry_time`, `added_by`) VALUES
(6, 'CG 16 AB 1234', 'Car', 2, '2026-02-27 12:03:28', 0),
(7, 'CG 16 CB 5478', 'Bike', 1, '2026-02-27 12:17:33', 0),
(8, 'CG 16 CB 8523', 'Truck', 4, '2026-02-27 12:18:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `violation`
--

CREATE TABLE `violation` (
  `violation_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `violation_type` varchar(50) DEFAULT NULL,
  `violation_date` date DEFAULT NULL,
  `violation_time` time DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `fine_amount` int(11) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violation`
--

INSERT INTO `violation` (`violation_id`, `vehicle_id`, `violation_type`, `violation_date`, `violation_time`, `recorded_by`, `fine_amount`, `payment_status`, `payment_date`, `due_date`) VALUES
(6, 6, 'Over Speed', '2026-02-27', '17:33:48', 1, 1000, 'paid', '2026-02-27', '2026-03-06'),
(7, 8, 'Signal Jump', '2026-02-27', '22:18:40', 2, 1500, 'pending', NULL, '2026-02-20'),
(8, 7, 'No Helmet', '2026-02-27', '22:20:04', 1, 500, 'pending', NULL, '2026-03-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fine_master`
--
ALTER TABLE `fine_master`
  ADD PRIMARY KEY (`violation_type`);

--
-- Indexes for table `traffic_signal`
--
ALTER TABLE `traffic_signal`
  ADD PRIMARY KEY (`signal_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- Indexes for table `violation`
--
ALTER TABLE `violation`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `violation_type` (`violation_type`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `traffic_signal`
--
ALTER TABLE `traffic_signal`
  MODIFY `signal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `violation`
--
ALTER TABLE `violation`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `violation`
--
ALTER TABLE `violation`
  ADD CONSTRAINT `violation_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`),
  ADD CONSTRAINT `violation_ibfk_3` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
