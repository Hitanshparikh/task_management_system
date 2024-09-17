-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2024 at 08:24 PM
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
-- Database: `tms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`) VALUES
(3, 'Vaibhavi Patel', 'All the Tasks for Vaibhavi Patel are here', 0, '2024-01-01', '2025-12-01', 13, '6', '2024-09-17 09:27:35'),
(4, 'Shrusti gajjar', '						All the tasks for Shrusti Gajjar are here.					', 0, '2024-01-01', '2025-12-01', 13, '7', '2024-09-17 09:32:21'),
(5, 'Abhishek Patel', '						All the tasks for Abhishek Patel are here.					', 0, '2024-01-01', '2025-12-01', 13, '9', '2024-09-17 09:33:57'),
(6, 'Pinal Hansora', '						All the tasks for Pinal Hansora are here.					', 0, '2024-01-01', '2025-12-01', 13, '10', '2024-09-17 09:38:44'),
(7, 'Avani Khokharia', 'All the tasks for Avani Khokharia are here.', 0, '2024-01-01', '2025-12-01', 13, '8', '2024-09-17 09:39:50'),
(8, 'Jigar Sarda', 'All the tasks for Jigar Sarda are here.', 0, '2024-01-01', '2025-12-01', 13, '12', '2024-09-17 09:40:47'),
(9, 'Harshul Yagnik', '						All the tasks for Harshul Yagnik are here.					', 0, '2024-01-11', '2025-12-01', 13, '11', '2024-09-17 09:42:43');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Task Management System', '23cs054@charusat.edu.in', 'none', 'Cspit Charusat', '');

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `description`, `status`, `date_created`) VALUES
(5, 3, 'submit exam papers', 'submit the checked papers by tomorrow', 1, '2024-09-17 09:28:44'),
(6, 5, 'test 1', '							', 1, '2024-09-17 09:51:25'),
(7, 7, 'test 2', '							', 1, '2024-09-17 09:52:56'),
(8, 9, 'test 3', '							', 1, '2024-09-17 09:53:11'),
(9, 8, 'test 4', '							', 1, '2024-09-17 09:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Amit', 'Thakkar', 'amit@hod', '17d84f171d54c301fabae1391a125c4e', 1, '1726477200_images.jpeg', '2020-11-26 10:57:04'),
(6, 'Vaibhavi', 'Patel', 'vaibhavipatel@charusat.in', '08f41e2b56730d87f1232d525303ba14', 3, '1726551000_VaibhaviPatel.webp', '2024-09-17 09:17:34'),
(7, 'Srushti', 'Gajjar', 'srushti@gajjar.charusat.in', '5dae429688af1c521ad87ac394192c6d', 3, '1726551060_SrushtiGajjar.webp', '2024-09-17 09:18:35'),
(8, 'avani', 'khokharia', 'avani@khokharia.charusat.in', '17540aef7b8470cc3ea8b2b9046af3b6', 3, '1726548120_download (2).jpeg', '2024-09-17 09:19:25'),
(9, 'Abhishek', 'patel', 'Abhishek@patel.charusat.in', '62c428533830d84fd8bc77bf402512fc', 3, '1726548060_download (1).jpeg', '2024-09-17 09:20:05'),
(10, 'Pinal', 'Hansora', 'pinal@hansora.charusat.in', 'da984e42a5899bbdac496ef0cbadcee2', 3, '1726550640_1725344474065.jpeg', '2024-09-17 09:21:01'),
(11, 'harshul', 'yagnik', 'harshul@yagnik.charusat.in', '035ed2311b96d2a65ec6a6fe71046c14', 3, '1726548000_download.jpeg', '2024-09-17 09:21:55'),
(12, 'Jigar', 'Sarda', 'Jigar@Sarda.charusat.in', '32981a13284db7a021131df49e6cd203', 3, '1726550640_images (1).jpeg', '2024-09-17 09:22:56'),
(13, 'Task', 'Manager', 'manager@cse', 'd6fd0924e324f50669ae0295adf59567', 2, '1726550700_360_F_227450952_KQCMShHPOPebUXklULsKsROk5AvN6H1H.jpg', '2024-09-17 09:26:28'),
(14, 'Hemang', 'Thakar', 'hemang@thakar.charusat.in', 'eb5e48e74123cacc52761302ea4a7d22', 3, '1726551000_HemangThakar.webp', '2024-09-17 11:00:36'),
(15, 'Vidisha', 'Pradhan', 'vidisha@pradhan.charusat.in', '08f41e2b56730d87f1232d525303ba14', 3, '1726551120_VidishaPradhan.webp', '2024-09-17 11:02:32'),
(16, 'Dhara', 'Solanki', 'dhara@solanki.charusat.in', '522748524ad010358705b6852b81be4c', 3, '1726551180_DharaSolanki.webp', '2024-09-17 11:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_productivity`
--

CREATE TABLE `user_productivity` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int(30) NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_productivity`
--
ALTER TABLE `user_productivity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
