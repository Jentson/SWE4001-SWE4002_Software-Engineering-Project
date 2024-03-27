-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2023 at 02:07 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.18

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
CREATE DATABASE IF NOT EXISTS `ez4leave` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ez4leave`;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `dept_ID` int(11) NOT NULL,
  `dept_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `leave_application`
--

DROP TABLE IF EXISTS `leave_application`;
CREATE TABLE `leave_application` (
  `id` int(11) NOT NULL,
  `leave_ref` varchar(20) NOT NULL,
  `stud_id` varchar(15) NOT NULL,
  `stud_name` text NOT NULL,
  `subj_code` varchar(15) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `documents` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_application`
--

INSERT INTO `leave_application` (`id`, `leave_ref`, `stud_id`, `stud_name`, `subj_code`, `startDate`, `endDate`, `reason`, `status`, `documents`) VALUES
(1, '1234', 'J21035942', 'Darren Huang', 'ICT30010', '2023-06-01', '2023-06-04', 'Sure', '', NULL),
(2, '1235', 'J21035942', 'Darren Huang', 'TNE20002', '2023-06-15', '2023-06-23', 'yes', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_assignment`
--

DROP TABLE IF EXISTS `leave_application_assignment`;
CREATE TABLE `leave_application_assignment` (
  `id` int(11) NOT NULL,
  `leave_application_id` int(11) DEFAULT NULL,
  `leave_ref` varchar(20) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

DROP TABLE IF EXISTS `lecturer`;
CREATE TABLE `lecturer` (
  `Lect_ID` int(10) NOT NULL,
  `Lect_name` varchar(40) NOT NULL,
  `Lect_email` varchar(40) NOT NULL,
  `Lect_pass` varchar(10) NOT NULL,
  `Role_ID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`Lect_ID`, `Lect_name`, `Lect_email`, `Lect_pass`, `Role_ID`) VALUES
(123456, 'Mohammad Faizal Alias', 'mfaizal.alias@newinti.edu.my', '123456', 2),
(123457, 'Shanmuga Sundram A Samy', 'shanmugas.asamy@newinti.edu.my', '123456', 2),
(123458, 'Chee Huei Ang', 'cheehuei.ang@newinti.edu.my', '123456', 2),
(123459, 'Siti Hawa Mohamed', 'sitihawa.msaid@newinti.edu.my', '123456', 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `Role_ID` int(2) NOT NULL,
  `Role_name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`Role_ID`, `Role_name`) VALUES
(1, 'Dean'),
(2, 'Lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `Sess_ID` int(11) NOT NULL,
  `Sess_Number` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `stud_id` varchar(15) NOT NULL,
  `stud_name` varchar(40) NOT NULL,
  `stud_email` varchar(40) NOT NULL,
  `stud_pass` varchar(42) NOT NULL,
  `dept_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_name`, `stud_email`, `stud_pass`, `dept_ID`) VALUES
('J21035942', 'Darren Huang', 'j21035942@student.newinti.edu.my', '*10148BCA8D75F638BBB0E297EC528A5CA14B1C07', 1),
('J21035943', 'Barry Allen', 'j21034953@student.newinti.edu.my', '$2y$10$Zi.xcHB/HwQfBKFxcpYcBuzEFB8t608QZ/t', 1),
('J21035944', 'Cindy Smith', 'j21035944@student.newinti.edu.my', '123456', 2),
('J21035945', 'Aloysius Phang', 'j21035945@student.newinti.edu.my', '123456', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `Subj_ID` varchar(10) NOT NULL,
  `Subj_Name` varchar(40) NOT NULL,
  `Lect_Id` int(10) NOT NULL,
  `Sess_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`Subj_ID`, `Subj_Name`, `Lect_Id`, `Sess_ID`) VALUES
('COS10009', 'Introduction to Programming', 123459, 1),
('COS10009', 'Introduction to Programming', 123459, 2),
('COS30019', 'Introduction to artificial intelligence', 123458, 1),
('COS30019', 'Introduction to artificial intelligence', 123458, 2),
('COS30019', 'Introduction to artificial intelligence', 123458, 3),
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
-- Indexes for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stud_id` (`stud_id`),
  ADD KEY `leave_ref` (`leave_ref`);

--
-- Indexes for table `leave_application_assignment`
--
ALTER TABLE `leave_application_assignment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_application_id_2` (`leave_application_id`),
  ADD UNIQUE KEY `leave_ref` (`leave_ref`),
  ADD KEY `leave_application_id` (`leave_application_id`),
  ADD KEY `lecturer_id` (`lecturer_id`),
  ADD KEY `leave_ref_2` (`leave_ref`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD UNIQUE KEY `Lect_id` (`Lect_ID`),
  ADD KEY `Role_ID` (`Role_ID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Role_ID`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`Sess_ID`);

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
  ADD PRIMARY KEY (`Subj_ID`,`Sess_ID`),
  ADD KEY `Lect_Id` (`Lect_Id`),
  ADD KEY `Sess_ID` (`Sess_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leave_application`
--
ALTER TABLE `leave_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leave_application_assignment`
--
ALTER TABLE `leave_application_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD CONSTRAINT `leave_application_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`);

--
-- Constraints for table `leave_application_assignment`
--
ALTER TABLE `leave_application_assignment`
  ADD CONSTRAINT `leave_application_assignment_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`Lect_ID`),
  ADD CONSTRAINT `leave_application_assignment_ibfk_2` FOREIGN KEY (`leave_ref`) REFERENCES `leave_application` (`leave_ref`);

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`Role_ID`) REFERENCES `roles` (`Role_ID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`dept_ID`) REFERENCES `department` (`dept_ID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`Sess_ID`) REFERENCES `session` (`Sess_ID`),
  ADD CONSTRAINT `subject_ibfk_2` FOREIGN KEY (`Lect_Id`) REFERENCES `lecturer` (`Lect_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
