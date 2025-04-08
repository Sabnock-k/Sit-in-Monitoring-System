-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 08, 2025 at 05:37 AM
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
-- Database: `sit_inms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sit_ins`
--

CREATE TABLE `sit_ins` (
  `student_id` varchar(100) NOT NULL,
  `laboratory` varchar(250) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_in_time` time NOT NULL,
  `check_out_time` time DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sit_ins`
--

INSERT INTO `sit_ins` (`student_id`, `laboratory`, `purpose`, `check_in_date`, `check_in_time`, `check_out_time`, `rating`, `feedback`) VALUES
('22652424', '524', 'C# Programming', '2025-03-25', '10:30:00', '12:45:00', 5, 'Boring'),
('22652424', '530', 'C Programming', '2025-04-03', '06:24:59', '06:40:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `idno` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `midname` varchar(100) DEFAULT NULL,
  `course` enum('Computer Science','Information Technology') NOT NULL,
  `year_level` enum('First year','Second year','Third year','Fourth year') NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `sessionno` int(11) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `idno`, `lastname`, `firstname`, `midname`, `course`, `year_level`, `email`, `address`, `username`, `sessionno`, `password_hash`, `created_at`) VALUES
(4, '22652424', 'Patino', 'Rafael', 'Bacarisas', 'Information Technology', 'Third year', 'sit-in@gmail.com', 'UC MAIN', 'Sabnock', 28, '$2y$10$oDLjVjrpeWJ4k0P4PD743u9hEfMD7Jfi.l7BR3havpbmceTp7xQwe', '2025-03-06 03:07:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
