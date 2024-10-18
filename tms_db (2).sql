-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 06:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(1, 'Amit Thakkar', 'Admin', 0, '2024-01-01', '2025-12-31', 2, '1', '2024-09-18 12:50:23'),
(2, 'Project Manager', 'Add or assign tasks and check progress.', 0, '2024-01-01', '2025-12-31', 2, '2', '2024-09-18 12:53:22'),
(3, 'Vaibhavi Patel', 'All the Tasks for Vaibhavi Patel are here', 0, '2024-01-01', '2025-12-01', 2, '3', '2024-09-17 09:27:35'),
(4, 'Shrusti gajjar', '						All the tasks for Shrusti Gajjar are here.					', 0, '2024-01-01', '2025-12-01', 2, '4', '2024-09-17 09:32:21'),
(5, 'Abhishek Patel', '						All the tasks for Abhishek Patel are here.					', 0, '2024-01-01', '2025-12-01', 2, '5', '2024-09-17 09:33:57'),
(6, 'Pinal Hansora', '						All the tasks for Pinal Hansora are here.					', 0, '2024-01-01', '2025-12-01', 2, '6', '2024-09-17 09:38:44'),
(7, 'Avani Khokharia', 'All the tasks for Avani Khokharia are here.', 0, '2024-01-01', '2025-12-01', 2, '7', '2024-09-17 09:39:50'),
(8, 'Jigar Sarda', 'All the tasks for Jigar Sarda are here.', 0, '2024-01-01', '2025-12-01', 2, '8', '2024-09-17 09:40:47'),
(9, 'Harshul Yagnik', '						All the tasks for Harshul Yagnik are here.					', 0, '2024-01-11', '2025-12-01', 2, '9', '2024-09-17 09:42:43'),
(10, 'Hemang Thakar', '&lt;font color=&quot;#000000&quot; face=&quot;sans-serif&quot;&gt;&lt;span style=&quot;font-size: 13.12px; white-space-collapse: preserve;&quot;&gt;All the tasks for Hemang Thakar are here.&lt;/span&gt;&lt;/font&gt;											', 0, '2024-01-01', '2025-12-31', 2, '10', '2024-09-18 13:08:19'),
(11, 'Vidisha Pradhan', '&lt;font color=&quot;#444444&quot; face=&quot;sans-serif&quot;&gt;&lt;span style=&quot;font-size: 13.12px; white-space-collapse: preserve;&quot;&gt;All the tasks for Vidisha Pradhan are here.&lt;/span&gt;&lt;/font&gt;											', 0, '2024-01-01', '2025-12-31', 2, '11', '2024-09-18 13:09:16'),
(12, 'Dhara Solanki', 'All the tasks for Dhara Solanki are here.											', 0, '2024-01-01', '2025-12-31', 2, '12', '2024-09-18 13:10:11'),
(13, 'Akshita Kadam', '&lt;div&gt;All the tasks for Akshita&lt;/div&gt;&lt;div&gt;Kadam are here.&lt;/div&gt;											', 0, '2024-01-01', '2025-12-31', 2, '13', '2024-09-18 13:10:47');

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
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `uploaded_file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `description`, `status`, `date_created`, `uploaded_file`) VALUES
(5, 3, 'submit exam papers', 'submit the checked papers by tomorrow', 1, '2024-09-17 09:28:44', ''),
(6, 5, 'test 1', '							', 1, '2024-09-17 09:51:25', ''),
(7, 7, 'test 2', '							', 1, '2024-09-17 09:52:56', ''),
(8, 9, 'test 3', '							', 1, '2024-09-17 09:53:11', ''),
(9, 8, 'test 4', '							', 1, '2024-09-17 09:53:25', ''),
(10, 5, 'submit papers', 'papers to be submitted by tomorrow', 2, '2024-09-18 10:23:11', ''),
(12, 5, 'zz', 'zz', 1, '2024-10-08 21:21:27', ''),
(13, 5, 'ss', '				ssssssssaaa			', 3, '2024-10-08 21:24:40', ''),
(14, 5, 'work', 'sss', 1, '2024-10-08 22:00:32', ''),
(16, 5, 'ssssss', 'sssss', 1, '2024-10-08 23:29:11', ''),
(17, 3, 'working??', 'ssss', 2, '2024-10-09 08:27:53', ''),
(18, 5, 'hitansh working??', 'hitansh', 2, '2024-10-09 09:13:02', ''),
(19, 5, 'work', 'wqwe', 1, '2024-10-09 10:00:54', ''),
(20, 5, 'ssdada', 'das', 1, '2024-10-17 22:39:04', '');

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
(1, 'Amit', 'Thakkar', 'amitthakkar.it@charusat.ac.in', '17d84f171d54c301fabae1391a125c4e', 1, '1.jpeg', '2020-11-26 10:57:04'),
(2, 'Task', 'Manager', 'manager@cse', 'd6fd0924e324f50669ae0295adf59567', 2, '2.jpg', '2024-09-17 09:26:28'),
(3, 'Vaibhavi', 'Patel', 'vaibhavipatel.cse@charusat.ac.in', '08f41e2b56730d87f1232d525303ba14', 3, '3.webp', '2024-09-17 09:17:34'),
(4, 'Srushti', 'Gajjar', 'srushtigajjar.cse@charusat.ac.in', '5dae429688af1c521ad87ac394192c6d', 3, '4.webp', '2024-09-17 09:18:35'),
(5, 'Abhishek', 'patel', 'abhishekpatel.cse@charusat.ac.in', '62c428533830d84fd8bc77bf402512fc', 3, '5.jpeg', '2024-09-17 09:20:05'),
(6, 'Pinal', 'Hansora', 'pinalhansora.cse@charusat.ac.in\r\n', 'da984e42a5899bbdac496ef0cbadcee2', 3, '6.jpeg', '2024-09-17 09:21:01'),
(7, 'avani', 'khokharia', 'avanikhokhariya.cse@charusat.ac.in', '17540aef7b8470cc3ea8b2b9046af3b6', 3, '7.jpeg', '2024-09-17 09:19:25'),
(8, 'Jigar', 'Sarda', 'jigarsarda.ee@charusat.ac.in', '32981a13284db7a021131df49e6cd203', 3, '8.jpeg', '2024-09-17 09:22:56'),
(9, 'harshul', 'yagnik', 'harshulyagnik.cse@charusat.ac.in\r\n', '035ed2311b96d2a65ec6a6fe71046c14', 3, '9.jpeg', '2024-09-17 09:21:55'),
(10, 'Hemang', 'Thakar', 'hemangthakar.cse@charusat.ac.in', 'eb5e48e74123cacc52761302ea4a7d22', 3, '10.webp', '2024-09-17 11:00:36'),
(11, 'Vidisha', 'Pradhan', 'vidishapradhan.cse@charusat.ac', '08f41e2b56730d87f1232d525303ba14', 3, '11.webp', '2024-09-17 11:02:32'),
(12, 'Dhara', 'Solanki', 'dharasolanki.cse@charusat.ac.in', '522748524ad010358705b6852b81be4c', 3, '12.webp', '2024-09-17 11:03:35'),
(13, 'Akshita', 'Kadam', 'akshitakadam.cse@charusat.ac.in', '17540aef7b8470cc3ea8b2b9046af3b6', 3, '13.webp', '2024-09-18 12:22:24'),
(25, 'admin', 'charusat', 'admin@123', '17d84f171d54c301fabae1391a125c4e', 1, '1728405660_download1.png', '2024-10-08 22:11:05');

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
-- Dumping data for table `user_productivity`
--

INSERT INTO `user_productivity` (`id`, `project_id`, `task_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `time_rendered`, `date_created`) VALUES
(5, 3, 5, 'started working on it', 'started working', '2024-09-18', '10:33:00', '14:33:00', 6, 4, '2024-09-18 10:34:02');

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
