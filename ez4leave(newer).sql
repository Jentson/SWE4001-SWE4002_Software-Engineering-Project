-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2024 at 10:02 AM
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
-- Database: `ez4leave`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_ID` int(11) NOT NULL,
  `dept_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_ID`, `dept_name`) VALUES
(1, 'Swinbrune University of Techno'),
(2, 'University of Hertfordshire'),
(3, 'Southern New Hampshire Univers'),
(4, 'Center of Art and Design');

-- --------------------------------------------------------

--
-- Table structure for table `hop_approval`
--

CREATE TABLE `hop_approval` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `hop_approval` varchar(20) NOT NULL,
  `process` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hop_approval`
--

INSERT INTO `hop_approval` (`id`, `leave_id`, `hop_approval`, `process`) VALUES
(1, 79, '123460', 0),
(2, 82, '123460', 0),
(3, 83, '123460', 0),
(4, 84, '123460', 0),
(5, 85, '123460', 0),
(6, 86, '123460', 0),
(7, 87, '123460', 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_application`
--

CREATE TABLE `leave_application` (
  `id` int(11) NOT NULL,
  `leave_ref` varchar(20) NOT NULL,
  `stud_id` varchar(15) NOT NULL,
  `stud_name` text NOT NULL,
  `subj_code` varchar(15) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `reason` text NOT NULL,
  `lecturer_approval_status` varchar(20) NOT NULL,
  `documents` blob DEFAULT NULL,
  `hop_approval` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_application`
--

INSERT INTO `leave_application` (`id`, `leave_ref`, `stud_id`, `stud_name`, `subj_code`, `startDate`, `endDate`, `reason`, `lecturer_approval_status`, `documents`, `hop_approval`, `status`) VALUES
(76, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-18', '2024-04-20', 't', 'Rejected', 0x2e2e2f66696c652f363631663564386634366561645f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(77, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-18', '2024-04-20', 't', 'Approved', 0x2e2e2f66696c652f363631663564386634366561645f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(78, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-19', '2024-04-20', 'Test1', 'Approved', 0x2e2e2f66696c652f363632306137356531376238615f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(79, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-19', '2024-04-20', 'Testing2', 'Approved', 0x2e2e2f66696c652f363632306165346630343232305f4c4541564520444f43554d454e5420544553542e706466, 'Approved', 1),
(80, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-19', '2024-04-20', 'Testing', 'Approved', 0x2e2e2f66696c652f363632313262353661336164645f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(81, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-19', '2024-04-20', 'Testing', 'Approved', 0x2e2e2f66696c652f363632313262353661336164645f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(82, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-19', '2024-04-20', 'Testing', 'Rejected', 0x2e2e2f66696c652f363632313262396364336236375f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(83, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-19', '2024-04-20', 'Testing', 'Approved', 0x2e2e2f66696c652f363632313262396364336236375f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 1),
(84, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS30019', '2024-04-19', '2024-04-20', 'tEST', 'Approved', 0x2e2e2f66696c652f363632313263326630336137365f4c4541564520444f43554d454e5420544553542e706466, 'Approved', 1),
(85, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-19', '2024-04-22', 'test', 'Pending', 0x2e2e2f66696c652f363632316531343663636230325f363632306137356531376238615f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 0),
(86, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-19', '2024-04-21', 'Test', 'Pending', 0x2e2e2f66696c652f363632316531616633393133305f363632306137356531376238615f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 0),
(87, 'J22036755', 'J22036755', 'Rodrikco Tiovandy', 'COS10009', '2024-04-01', '2024-04-09', 'test', 'Pending', 0x2e2e2f66696c652f363632316535666663613530355f363632306165346630343232305f4c4541564520444f43554d454e5420544553542e706466, 'Pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_approval`
--

CREATE TABLE `lecturer_approval` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `lect_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer_approval`
--

INSERT INTO `lecturer_approval` (`id`, `leave_id`, `lecturer_id`, `lect_status`) VALUES
(8, 76, 123460, 1),
(9, 77, 123460, 1),
(10, 78, 123460, 1),
(11, 79, 123460, 1),
(12, 80, 123460, 1),
(13, 81, 123460, 1),
(14, 82, 123460, 1),
(15, 83, 123460, 1),
(16, 84, 123460, 1),
(17, 85, 123460, 0),
(18, 86, 123460, 0),
(19, 87, 123460, 0);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `Sess_ID` int(11) NOT NULL,
  `Sess_Number` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`Sess_ID`, `Sess_Number`) VALUES
(1, 'C1'),
(2, 'C2'),
(3, 'C3'),
(4, 'S1'),
(5, 'S2');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(10) NOT NULL,
  `staff_name` varchar(40) NOT NULL,
  `staff_email` varchar(40) NOT NULL,
  `staff_pass` varchar(80) NOT NULL,
  `roles` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_email`, `staff_pass`, `roles`) VALUES
(0, 'Sure', 'Sure@newinti.edu.my', '$2y$10$yGWWmiEcrbPUbQL5iiADi.MveDXLY6pjFqX7DOkWC7brhCB.r0KaC', 'Admin'),
(123456, 'Mohammad Faizal Alias', 'mfaizal.alias@newinti.edu.my', '123456', ''),
(123457, 'Shanmuga Sundram A Samy', 'shanmugas.asamy@newinti.edu.my', '123456', ''),
(123458, 'Chee Huei Ang', 'cheehuei.ang@newinti.edu.my', '123456', ''),
(123459, 'Siti Hawa Mohamed', 'sitihawa.msaid@newinti.edu.my', '123456', ''),
(123460, 'Rod', 'rod@newinti.edu.my', '$2y$10$q7PhzE6UxZJrIyaQ.SwgPeryQEnvJL21ygXNu5daMRDFy79w3GROW', 'Lecturer'),
(123462, 'Rod', 'rod@newinti.edu.my', '$2y$10$eKrSNrHaW1CWVY9bVBVSxuxZhqLxt1AfTXsprb/iFlN16OxmJMQY.', 'HOP');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` varchar(15) NOT NULL,
  `stud_name` varchar(40) NOT NULL,
  `stud_email` varchar(40) NOT NULL,
  `stud_pass` varchar(80) NOT NULL,
  `dept_ID` int(11) NOT NULL,
  `session` varchar(20) NOT NULL,
  `programme` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone` int(20) NOT NULL,
  `semester` int(2) NOT NULL,
  `major` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_name`, `stud_email`, `stud_pass`, `dept_ID`, `session`, `programme`, `address`, `phone`, `semester`, `major`) VALUES
('J22036755', 'Rodrikco Tiovandy', 'J22036755@student.newinti.edu.my', '$2y$10$3r1XE39R0xYysvDeJypU7.sDgx7DQin0TMl5OgwyXdWpFfnYoxGQK', 1, 'FEB2024', 'BCSSUT', 'SS15/6A', 172608213, 5, 'CyberSecurity');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subj_code` varchar(10) NOT NULL,
  `subj_name` varchar(40) NOT NULL,
  `staff_id` int(6) NOT NULL,
  `Sess_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subj_code`, `subj_name`, `staff_id`, `Sess_ID`) VALUES
('COS10009', 'Introduction to Programming', 123460, 1),
('COS30019', 'Introduction to artificial intelligence', 123460, 1),
('ICT30010', 'E-Forensics', 123456, 1),
('SWE20001', 'Managing software projects', 123456, 1),
('TNE20002', 'Network Routing Principles', 123457, 1),
('TNE20002', 'Network Routing Principles', 123457, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_ID`);

--
-- Indexes for table `hop_approval`
--
ALTER TABLE `hop_approval`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_id` (`leave_id`);

--
-- Indexes for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stud_id` (`stud_id`);

--
-- Indexes for table `lecturer_approval`
--
ALTER TABLE `lecturer_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_id` (`leave_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`Sess_ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`) USING BTREE;

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`),
  ADD KEY `dept_ID` (`dept_ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subj_code`,`Sess_ID`),
  ADD KEY `Lect_Id` (`staff_id`),
  ADD KEY `Sess_ID` (`Sess_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hop_approval`
--
ALTER TABLE `hop_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leave_application`
--
ALTER TABLE `leave_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `lecturer_approval`
--
ALTER TABLE `lecturer_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD CONSTRAINT `leave_application_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `lecturer_approval`
--
ALTER TABLE `lecturer_approval`
  ADD CONSTRAINT `lecturer_approval_ibfk_1` FOREIGN KEY (`leave_id`) REFERENCES `leave_application` (`id`),
  ADD CONSTRAINT `lecturer_approval_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
