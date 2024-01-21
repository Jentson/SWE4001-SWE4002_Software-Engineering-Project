-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2024 at 02:51 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intiservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookingschedule`
--

CREATE TABLE `bookingschedule` (
  `bookingID` int(50) NOT NULL,
  `staffID` varchar(50) NOT NULL,
  `studentID` varchar(50) NOT NULL,
  `activityDate` date NOT NULL,
  `activityTime` time NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_hop`
--

CREATE TABLE `leave_application_hop` (
  `leave_app_id` int(50) NOT NULL,
  `leave_ref` varchar(50) NOT NULL,
  `staff_id` int(50) NOT NULL,
  `status` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_lecturer`
--

CREATE TABLE `leave_application_lecturer` (
  `leave_id` int(50) NOT NULL,
  `leave_ref` varchar(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject_id` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `document` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(50) NOT NULL,
  `position_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position_id`, `position_name`) VALUES
(1, 'HOP'),
(2, 'Lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(50) NOT NULL,
  `program_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`) VALUES
(1, 'SUT');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(50) NOT NULL,
  `section_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` varchar(50) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_email` varchar(255) NOT NULL,
  `staff_pass` varchar(255) NOT NULL,
  `position_id` int(50) NOT NULL,
  `program_id` int(50) NOT NULL,
  `gender` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_email`, `staff_pass`, `position_id`, `program_id`, `gender`) VALUES
('123456', 'Abu', 'aaa123@gmail.com', '$2y$10$cNxxENwJk6ujzr8hyANWbeWqkJC1Po.F6P3ZBjoYyiu', 1, 1, 'male'),
('223456', 'Abuu', 'aaa111@gmail.com', '$2y$10$0AzDziwhO5EnpWAWlwoQn.V/2bAH8UF86IUCTJk6jNC3lUA3IETwC', 1, 1, 'male');

-- --------------------------------------------------------

--
-- Table structure for table `stafftimeschedule`
--

CREATE TABLE `stafftimeschedule` (
  `timeID` int(50) NOT NULL,
  `staffID` varchar(50) NOT NULL,
  `scheduleDate` date NOT NULL,
  `scheduleDay` varchar(15) CHARACTER SET latin1 NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `bookAvail` varchar(10) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stafftimeschedule`
--

INSERT INTO `stafftimeschedule` (`timeID`, `staffID`, `scheduleDate`, `scheduleDay`, `startTime`, `endTime`, `bookAvail`) VALUES
(2, '', '2024-01-29', 'Monday', '12:50:00', '00:00:00', 'available'),
(3, '', '2024-01-23', 'Monday', '10:50:00', '00:00:00', 'available'),
(4, '', '2024-01-29', 'Monday', '13:20:00', '00:00:00', 'available'),
(5, '', '2024-01-23', 'Monday', '08:20:00', '00:00:00', 'available'),
(6, '', '2024-01-15', 'Monday', '10:50:00', '00:00:00', 'available'),
(7, '', '2024-01-29', 'Monday', '10:40:00', '00:00:00', 'available'),
(8, '', '2024-01-29', 'Monday', '10:35:00', '00:00:00', 'available'),
(9, '', '2024-01-15', 'Monday', '08:50:00', '00:00:00', 'available'),
(10, '', '2024-01-31', 'Monday', '10:44:00', '00:00:00', 'available'),
(11, '', '2024-01-29', 'Monday', '09:40:00', '00:00:00', 'available'),
(12, '', '2024-01-15', 'Monday', '10:55:00', '00:00:00', 'available'),
(13, '', '2024-01-30', 'Monday', '03:30:00', '00:00:00', 'available'),
(14, '', '2024-01-29', 'Monday', '10:20:00', '10:30:00', 'available'),
(15, '', '2024-01-29', 'Monday', '10:10:00', '10:30:00', 'available'),
(16, '', '2024-01-29', 'Monday', '15:30:00', '15:50:00', 'available'),
(17, '', '2024-01-29', 'Monday', '10:30:00', '10:50:00', 'available'),
(18, '', '0000-00-00', '', '00:00:00', '00:00:00', ''),
(19, '', '2024-01-29', 'Monday', '14:30:00', '14:50:00', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(50) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_pass` varchar(50) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `program_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `student_email`, `student_pass`, `gender`, `program_id`) VALUES
('J12345678', 'Ali', 'abc123@gmail.com', '$2y$10$J12345678abcdefghijklmnopqrstu', 'male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(50) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `section_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `timesession`
--

CREATE TABLE `timesession` (
  `sessionID` int(50) NOT NULL,
  `timeID` int(50) NOT NULL,
  `timeList` varchar(100) NOT NULL,
  `day` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookingschedule`
--
ALTER TABLE `bookingschedule`
  ADD PRIMARY KEY (`bookingID`),
  ADD KEY `staffID` (`staffID`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `leave_application_hop`
--
ALTER TABLE `leave_application_hop`
  ADD PRIMARY KEY (`leave_app_id`),
  ADD KEY `leave_ref` (`leave_ref`),
  ADD KEY `lecturer_id` (`staff_id`);

--
-- Indexes for table `leave_application_lecturer`
--
ALTER TABLE `leave_application_lecturer`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `leave_ref` (`leave_ref`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `stafftimeschedule`
--
ALTER TABLE `stafftimeschedule`
  ADD PRIMARY KEY (`timeID`),
  ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `timesession`
--
ALTER TABLE `timesession`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `timeID` (`timeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leave_application_lecturer`
--
ALTER TABLE `leave_application_lecturer`
  MODIFY `leave_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafftimeschedule`
--
ALTER TABLE `stafftimeschedule`
  MODIFY `timeID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookingschedule`
--
ALTER TABLE `bookingschedule`
  ADD CONSTRAINT `bookingschedule_ibfk_1` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `bookingschedule_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `leave_application_hop`
--
ALTER TABLE `leave_application_hop`
  ADD CONSTRAINT `leave_application_hop_ibfk_1` FOREIGN KEY (`leave_ref`) REFERENCES `leave_application_lecturer` (`leave_ref`);

--
-- Constraints for table `leave_application_lecturer`
--
ALTER TABLE `leave_application_lecturer`
  ADD CONSTRAINT `leave_application_lecturer_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `leave_application_lecturer_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `position` (`position_id`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
