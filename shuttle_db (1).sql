-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 02, 2026 at 10:22 AM
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
-- Database: `shuttle_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'joemarlagbas5@gmail.com', '$2y$10$XIsUAckOGGDWd8TUGAqRlOW3NasqCwdMSPtCNxwuChsk2EsghfriK');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Joemar Lagbas', 'joemarlagbas5@gmail.com', 'hahahahaha panget', '2026-01-21 12:46:13'),
(2, 'Joemar Lagbas', 'joemarlagbas5@gmail.com', 'hihihih', '2026-01-21 13:17:48'),
(3, 'joenel', 'joemarlagbas5@gmail.com', 'Panget KO', '2026-01-22 03:05:40'),
(4, 'John john', 'John_PogiLangSakalam@gmail.com', 'Ganda ng service nyo', '2026-02-13 15:03:04'),
(5, 'Joemar Lagbas JUSTIN', 'joemarlagbas5@gmail.com', 'GWAPO NI CHRISTIAN', '2026-02-22 10:41:05'),
(6, 'Joemar Lagbas JUSTIN', 'joemarlagbas5@gmail.com', 'GWAPO NI CHRISTIAN', '2026-02-22 10:41:07'),
(7, 'Joemar Lagbas JUSTIN', 'joemarlagbas5@gmail.com', 'GWAPO NI CHRISTIAN', '2026-02-22 10:41:07'),
(8, 'Joemar Lagbas JUSTIN', 'joemarlagbas5@gmail.com', 'GWAPO NI CHRISTIAN', '2026-02-22 10:41:07'),
(9, 'Joemar Lagbas', 'JOsh@jsja', 'hahaha\r\n', '2026-03-02 04:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `device_keys`
--

CREATE TABLE `device_keys` (
  `id` int(11) NOT NULL,
  `shuttle_id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_accounts`
--

CREATE TABLE `driver_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shuttle_id` int(11) DEFAULT NULL,
  `tracking_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_accounts`
--

INSERT INTO `driver_accounts` (`id`, `username`, `password`, `approved`, `created_at`, `shuttle_id`, `tracking_status`) VALUES
(3, 'joemarlagbas5@gmail.com', '$2y$10$7Df.VB8cR.m5ZP9mR2Z2MeQ65MZG40eAXOT7ylihxuM8haNH7Z63W', 1, '2026-02-27 08:01:00', NULL, 0),
(5, 'joeamr@sfkaf', '$2y$10$bNkvq.qkBo6qMnXjM87tgeQ4QtvDbvyqmtJe78AGqUyGTOTxdJpdO', 0, '2026-02-27 10:27:17', NULL, 0),
(7, 'jo@lagbas', '$2y$10$WKr0i7UkRLj8V2FHy0FjyuiugGI3hEW3gbrhZpeiPhpqqk5PQH/Qu', 0, '2026-02-27 10:28:36', NULL, 0),
(9, 'JustinMiral@gmail.com', '$2y$10$yNZR5vMGzYZRcd2tso06AeNZ5021j7ObxS1yibDYGvVZ9bKO.G9vC', 1, '2026-02-27 11:35:37', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `driver_notifications`
--

CREATE TABLE `driver_notifications` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` enum('pending','read') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver_notifications`
--

INSERT INTO `driver_notifications` (`id`, `driver_id`, `message`, `status`, `created_at`) VALUES
(1, 3, 'Driver joemarlagbas5@gmail.com requests location access for GPS tracking.', 'pending', '2026-02-27 11:17:29'),
(2, 9, 'Driver JustinMiral@gmail.com requests location access for GPS tracking.', 'pending', '2026-02-27 11:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `shuttle_id` int(11) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `shuttle_id`, `latitude`, `longitude`, `updated_at`, `driver_id`) VALUES
(1, 20, 14.3227, 121.022, '2026-02-27 12:00:08', 0),
(4, 20, 14.3227, 121.022, '2026-02-27 12:00:10', 0),
(5, 20, 14.3227, 121.022, '2026-02-27 12:00:14', 0),
(6, 20, 14.3227, 121.022, '2026-02-27 12:00:16', 0),
(7, 20, 14.3227, 121.022, '2026-02-27 12:01:44', 0),
(8, 20, 14.29, 121.06, '2026-02-27 12:11:59', 0),
(10, 20, 14.3227, 121.022, '2026-02-27 12:12:02', 0),
(11, 20, 14.3227, 121.022, '2026-02-27 12:12:19', 0),
(12, 20, 14.3227, 121.022, '2026-02-27 12:12:29', 0),
(13, 20, 14.3227, 121.022, '2026-02-27 12:12:38', 0),
(14, 20, 14.3227, 121.022, '2026-02-27 12:12:40', 0),
(15, 19, 14.3226, 121.022, '2026-02-27 12:59:19', 3),
(16, 19, 14.3228, 121.022, '2026-02-27 13:00:24', 3),
(17, 19, 14.29, 121.06, '2026-02-27 13:01:19', 3),
(19, 19, 14.3228, 121.022, '2026-02-27 13:01:23', 3),
(20, 19, 14.3228, 121.022, '2026-02-27 13:01:29', 3),
(21, 19, 14.3229, 121.022, '2026-02-27 13:19:02', 3),
(22, 19, 14.3228, 121.022, '2026-02-27 13:20:19', 3),
(23, 19, 14.3228, 121.022, '2026-02-27 13:21:35', 3),
(24, 19, 14.3228, 121.022, '2026-02-27 13:24:27', 3),
(25, 19, 14.3228, 121.022, '2026-02-27 13:24:34', 3),
(26, 19, 14.3229, 121.022, '2026-02-27 13:28:42', 3),
(27, 19, 14.3229, 121.022, '2026-02-27 13:28:52', 3),
(28, 19, 14.3228, 121.022, '2026-02-27 13:29:42', 3),
(29, 19, 14.3228, 121.022, '2026-02-27 13:30:01', 3),
(30, 19, 14.3228, 121.022, '2026-02-27 13:30:02', 3),
(31, 19, 14.3225, 121.022, '2026-02-27 13:31:49', 3),
(32, 19, 14.3225, 121.022, '2026-02-27 13:32:30', 3),
(33, 19, 14.3229, 121.022, '2026-02-27 13:33:15', 3),
(34, 19, 14.3229, 121.022, '2026-02-27 13:33:17', 3),
(35, 19, 14.3229, 121.022, '2026-02-27 13:33:29', 3),
(36, 19, 14.3227, 121.022, '2026-02-27 13:34:50', 3),
(38, 19, 14.3227, 121.022, '2026-02-27 13:35:22', 3),
(39, 19, 14.3228, 121.022, '2026-02-27 13:36:24', 3),
(40, 19, 14.3228, 121.022, '2026-02-27 13:36:41', 3),
(41, 19, 14.3229, 121.022, '2026-02-27 13:37:57', 3),
(42, 19, 14.3229, 121.022, '2026-02-27 13:38:01', 3),
(43, 19, 14.3229, 121.022, '2026-02-27 13:38:05', 3),
(44, 19, 14.3229, 121.022, '2026-02-27 13:41:32', 3),
(45, 19, 14.3226, 121.022, '2026-02-27 13:43:15', 3),
(46, 19, 14.3229, 121.022, '2026-02-27 13:43:55', 3),
(47, 19, 14.3229, 121.022, '2026-02-27 13:43:57', 3),
(48, 19, 14.3229, 121.022, '2026-02-27 13:43:59', 3),
(49, 19, 14.3228, 121.022, '2026-02-27 13:45:49', 3),
(50, 19, 14.3228, 121.022, '2026-02-27 13:45:54', 3),
(51, 19, 14.3228, 121.022, '2026-02-27 13:46:14', 3),
(52, 19, 14.3228, 121.022, '2026-02-27 13:46:34', 3),
(53, 19, 14.3229, 121.022, '2026-02-27 13:47:34', 3),
(54, 19, 14.3229, 121.022, '2026-02-27 13:49:35', 3),
(55, 19, 14.3229, 121.022, '2026-02-27 13:49:51', 3),
(56, 19, 14.3229, 121.022, '2026-02-27 13:50:18', 3),
(57, 19, 14.3229, 121.022, '2026-02-27 13:50:21', 3),
(58, 19, 14.3229, 121.022, '2026-02-27 13:50:24', 3),
(59, 19, 14.29, 121.06, '2026-02-27 23:32:38', 3),
(61, 19, 14.3228, 121.022, '2026-02-27 23:33:44', 3),
(62, 21, 14.3105, 121.022, '2026-03-02 07:47:58', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `route` varchar(255) NOT NULL,
  `first_trip` varchar(50) NOT NULL,
  `last_trip` varchar(50) NOT NULL,
  `frequency` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `route`, `first_trip`, `last_trip`, `frequency`) VALUES
(2, 'Mandarin Homes GMA Cavite - Missiah Highschool', '6:15 AM', '6:15 PM', 'Every 30 minutes'),
(3, 'Downtown Cavite - Missiah Highschool', '6:30 AM', '6:30 PM', 'Every 45 minutes'),
(4, 'Magra - Bernardo Pulido', '12:30', '13:00', 'Every 30 minutes');

-- --------------------------------------------------------

--
-- Table structure for table `shuttles`
--

CREATE TABLE `shuttles` (
  `id` int(11) NOT NULL,
  `shuttle_name` varchar(100) DEFAULT NULL,
  `plate_number` varchar(50) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shuttles`
--

INSERT INTO `shuttles` (`id`, `shuttle_name`, `plate_number`, `status`) VALUES
(21, 'L300', 'kasdhsf', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `shuttle_assignments`
--

CREATE TABLE `shuttle_assignments` (
  `id` int(11) NOT NULL,
  `shuttle_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shuttle_assignments`
--

INSERT INTO `shuttle_assignments` (`id`, `shuttle_id`, `driver_id`, `status`, `assigned_at`) VALUES
(8, 21, 9, 'Active', '2026-03-02 04:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `site_content`
--

CREATE TABLE `site_content` (
  `id` int(11) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_content`
--

INSERT INTO `site_content` (`id`, `section`, `title`, `content`) VALUES
(1, 'home', 'Welcome to My Messiah School of Cavitel Shuttle Tracker  JUSTIN', 'Track your shuttle in real-time.'),
(2, 'about', 'About brother. John', 'Kaka birthday lang kahapon\r\nGwapo padin\r\nLolo na\r\nTito ko sya'),
(3, 'schedule', 'Shuttle Schedule', 'Schedule managed by admin'),
(4, 'tracking', 'How to Track', 'Steps on how to track the shuttle'),
(5, 'contact', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `site_images`
--

CREATE TABLE `site_images` (
  `id` int(11) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_images`
--

INSERT INTO `site_images` (`id`, `section`, `image`) VALUES
(4, 'home', '1768996706_My Messiah (1).png'),
(5, 'about', '1770994684_4.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','driver') NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'joemarlagbas5@gmail.com', '$2y$10$PVg2mec5OADHvBof55wp/.2B3iqeOeseOiSPGyays79rfyy1VukkG', '2026-03-02 14:49:42', '2026-03-02 14:49:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_keys`
--
ALTER TABLE `device_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shuttle_id` (`shuttle_id`);

--
-- Indexes for table `driver_accounts`
--
ALTER TABLE `driver_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `driver_notifications`
--
ALTER TABLE `driver_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shuttle_id` (`shuttle_id`,`updated_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shuttles`
--
ALTER TABLE `shuttles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD UNIQUE KEY `plate_number_2` (`plate_number`);

--
-- Indexes for table `shuttle_assignments`
--
ALTER TABLE `shuttle_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assignment_shuttle` (`shuttle_id`),
  ADD KEY `fk_assignment_driver` (`driver_id`);

--
-- Indexes for table `site_content`
--
ALTER TABLE `site_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_images`
--
ALTER TABLE `site_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `device_keys`
--
ALTER TABLE `device_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_accounts`
--
ALTER TABLE `driver_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `driver_notifications`
--
ALTER TABLE `driver_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shuttles`
--
ALTER TABLE `shuttles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `shuttle_assignments`
--
ALTER TABLE `shuttle_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `site_content`
--
ALTER TABLE `site_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `site_images`
--
ALTER TABLE `site_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device_keys`
--
ALTER TABLE `device_keys`
  ADD CONSTRAINT `device_keys_ibfk_1` FOREIGN KEY (`shuttle_id`) REFERENCES `shuttles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `driver_notifications`
--
ALTER TABLE `driver_notifications`
  ADD CONSTRAINT `driver_notifications_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `driver_accounts` (`id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shuttle_assignments`
--
ALTER TABLE `shuttle_assignments`
  ADD CONSTRAINT `fk_assignment_shuttle` FOREIGN KEY (`shuttle_id`) REFERENCES `shuttles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
