-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 16, 2025 at 02:51 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1211002_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` varchar(10) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `total_budget` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `document_title1` varchar(255) DEFAULT NULL,
  `document_title2` varchar(255) DEFAULT NULL,
  `document_title3` varchar(255) DEFAULT NULL,
  `supporting_document1` longblob,
  `supporting_document2` longblob,
  `supporting_document3` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `total_budget`, `start_date`, `end_date`, `document_title1`, `document_title2`, `document_title3`, `supporting_document1`, `supporting_document2`, `supporting_document3`) VALUES
('PROJ-001', 'Website Redesign', 'Redesign the company website for better usability.', 'Tech Corp', 15000.00, '2024-01-01', '2024-03-01', 'UI Mockups', 'Wireframes', 'Final Design', 0x6d6f636b7570732e706466, 0x776972656672616d65732e706466, 0x66696e616c5f64657369676e2e706466),
('PROJ-002', 'Mobile App Development', 'Develop a mobile app for e-commerce.', 'Shopify', 25000.00, '2024-02-01', '2024-06-01', 'App Architecture', 'Prototype', 'User Feedback', 0x6172636869746563747572652e706466, 0x70726f746f747970652e706466, 0x666565646261636b2e706466),
('PROJ-003', 'Marketing Campaign', 'Plan and execute a marketing campaign for the product launch.', 'Marketing Inc.', 10000.00, '2024-03-01', '2024-05-01', 'Campaign Plan', 'Ad Materials', 'Budget Sheet', 0x63616d706169676e5f706c616e2e706466, 0x61645f6d6174657269616c732e706466, 0x6275646765745f73686565742e706466);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `effort` int DEFAULT '0',
  `project_id` varchar(10) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `start_date`, `end_date`, `effort`, `project_id`, `status`, `priority`) VALUES
(6, 'Design Mockups', 'Create UI/UX mockups for the website.', '2024-01-05', '2024-01-15', -1, 'PROJ-001', 'Pending', 'High'),
(7, 'Backend Development', 'Develop backend APIs for the website.', '2024-01-16', '2024-02-15', -1, 'PROJ-001', 'In Progress', 'Medium'),
(8, 'Frontend Development', 'Develop frontend for the website.', '2024-02-01', '2024-03-01', -1, 'PROJ-001', 'Pending', 'High'),
(9, 'App UI Design', 'Design UI for the mobile app.', '2024-02-05', '2024-02-25', -1, 'PROJ-002', 'Pending', 'High'),
(10, 'App Backend', 'Develop backend services for the mobile app.', '2024-03-01', '2024-05-01', -1, 'PROJ-002', 'Pending', 'Medium');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('Manager','Project Leader','Team Member') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `flat` varchar(50) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `idnumber` char(10) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `skills` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `role`, `username`, `password`, `flat`, `street`, `city`, `country`, `dob`, `idnumber`, `telephone`, `qualification`, `skills`) VALUES
(12, 'Manager User', 'manager@example.com', 'Manager', 'hala_manager', 'hala123456789', '12A', 'Main Street', 'Ramallah', 'Palestine', '1980-01-01', '123456789', '0590000001', 'MBA', 'fvfd'),
(13, 'Dania Mahmoud', 'leader@example.com', 'Project Leader', 'dania_leader', 'dania123456789', '23B', '2nd Street', 'Nablus', 'Palestine', '1985-05-15', '234567890', '0590000002', 'BSc Computer Science', 'Team Management, Planning'),
(14, 'Jenan Ahmad', 'member1@example.com', 'Team Member', 'jenan_member', 'jenan123456789', '34C', '3rd Street', 'Bethlehem', 'Palestine', '1990-07-20', '345678901', '0590000003', 'BSc Engineering', 'Coding, Testing'),
(15, 'Haneen Naser', 'member2@example.com', 'Team Member', 'haneen_member', 'haneen123456789', '45D', '4th Street', 'Hebron', 'Palestine', '1992-11-11', '456789012', '0590000004', 'BSc Mathematics', 'Documentation, Reporting'),
(16, 'Salma Mohammed', 'member3@example.com', 'Team Member', 'salma_member', 'salma123456789', '56E', '5th Street', 'Gaza', 'Palestine', '1995-09-30', '567890123', '0590000005', 'BSc IT', 'Frontend Development, UI/UX');

-- --------------------------------------------------------

--
-- Table structure for table `user_tasks`
--

CREATE TABLE `user_tasks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `task_id` int NOT NULL,
  `role` enum('Developer','Tester','Designer','Analyst') NOT NULL,
  `contribution_percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD UNIQUE KEY `project_id` (`project_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `idnumber` (`idnumber`);

--
-- Indexes for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_tasks`
--
ALTER TABLE `user_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD CONSTRAINT `user_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_tasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
