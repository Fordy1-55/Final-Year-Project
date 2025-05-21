-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 12:48 PM
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
-- Database: `martial_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `belt_requirements`
--

CREATE TABLE `belt_requirements` (
  `id` int(11) NOT NULL,
  `belt` varchar(50) NOT NULL,
  `requirement` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `belt_requirements`
--

INSERT INTO `belt_requirements` (`id`, `belt`, `requirement`) VALUES
(13, 'white', 'Front Kick'),
(14, 'white', 'Side Kick'),
(15, 'white', 'Jab'),
(17, 'red', 'Round-House Kick'),
(19, 'yellow', '20 Push-ups, 20 Sit-ups & 20 Squats'),
(20, 'orange', '5x Min Horse Straddle Stance'),
(21, 'green', 'Back Kick'),
(22, 'blue', 'Jumping Front Kick'),
(23, 'purple', 'Spinning Leg Sweep'),
(24, 'brown', 'Tornado Kick'),
(25, 'brown and black', 'Blitz'),
(26, 'black', 'whirlwind kick');

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `belt` varchar(50) NOT NULL,
  `requirement` varchar(255) NOT NULL,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `progress`
--

INSERT INTO `progress` (`id`, `student_id`, `belt`, `requirement`, `completed`) VALUES
(20, 18, 'white', 'Front Kick', 0),
(21, 18, 'white', 'Side Kick', 0),
(22, 18, 'white', 'Jab', 0),
(23, 18, 'yellow', '20 Push-ups, 20 Sit-ups & 20 Squats', 0),
(24, 18, 'orange', '5x Min Horse Straddle Stance', 0),
(25, 18, 'green', 'Back Kick', 0),
(26, 18, 'blue', 'Jumping Front Kick', 0),
(27, 18, 'purple', 'Spinning Leg Sweep', 0),
(28, 18, 'brown', 'Tornado Kick', 0),
(29, 18, 'brown and black', 'Blitz', 0),
(30, 18, 'black', 'whirlwind kick', 0);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `class_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `date`, `start_time`, `end_time`, `class_name`) VALUES
(8, '2025-05-30', '12:00:00', '13:00:00', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('parent','student','sensei') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `belt` varchar(50) DEFAULT 'white'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`, `parent_id`, `belt`) VALUES
(16, 'parent1', '$2y$10$lElT9EkGas/h1dbuEJcOrexRUiKS4hEgGVjJ.lmTbbi0w4Ptst2dC', 'parent', '2025-05-21 09:22:17', 'parent1@gmail.com', NULL, 'white'),
(17, 'sensei1', '$2y$10$ub6nG79CmbZb8sF9dLZQfOn5RhwiH8EenMpYs/U/AwhBVfkn1Ke3m', 'sensei', '2025-05-21 09:22:35', 'sensei1@gmail.com', NULL, 'white'),
(18, 'student1', '$2y$10$ck.J78ikQzALPAYvFSNi1.fKlVEnBgD81yzHslkwSGTK14/RQuhEK', 'student', '2025-05-21 09:23:12', 'student1@gmail.com', 16, 'white');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `belt_requirements`
--
ALTER TABLE `belt_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `belt_requirements`
--
ALTER TABLE `belt_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `progress`
--
ALTER TABLE `progress`
  ADD CONSTRAINT `progress_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
