-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2024 at 05:01 PM
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
-- Database: `intiservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `modal` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `booking_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_history`
--

INSERT INTO `appointment_history` (`id`, `student_id`, `student_name`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `staff_id`, `booking_timestamp`) VALUES
(17, 666666, 'ng', '2024-04-17', '12:30:00', '12:50:00', 'F2F', 'booked', 666666, '2024-04-13 07:55:25'),
(18, 666666, 'ng', '2024-04-17', '12:30:00', '12:50:00', 'F2F', 'cancelled', 666666, '2024-04-13 07:55:27'),
(19, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'booked', 111111, '2024-04-13 08:09:33'),
(20, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'cancelled', 111111, '2024-04-13 08:09:46'),
(21, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'booked', 111111, '2024-04-13 08:11:29'),
(22, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'cancelled', 111111, '2024-04-13 08:13:19'),
(23, 666666, 'ng', '2024-05-01', '11:30:00', '00:30:00', 'Online', 'booked', 111111, '2024-04-13 08:14:13'),
(24, 666666, 'ng', '2024-04-23', '12:45:00', '13:35:00', 'F2F', 'booked', 111111, '2024-04-13 14:19:15'),
(25, 666666, 'ng', '2024-05-07', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:19:44'),
(26, 666666, 'ng', '2024-05-14', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:21:46'),
(27, 666666, 'ng', '2024-05-14', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:22:09'),
(28, 666666, 'ng', '2024-05-14', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:22:10'),
(29, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:23:37'),
(30, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:27:03'),
(31, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:27:05'),
(32, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:31:12'),
(33, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:31:15'),
(34, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:39:24'),
(35, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:40:06'),
(36, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:40:08'),
(37, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:41:13'),
(38, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:41:15'),
(39, 666666, 'ng', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:42:56'),
(40, 666666, 'ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:43:06'),
(41, 666666, 'ng', '2024-05-07', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '2024-04-13 14:43:28'),
(42, 333333, 'test', '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '2024-04-13 14:56:11');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_application_hop`
--

CREATE TABLE `leave_application_hop` (
  `leave_app_id` int(50) NOT NULL,
  `leave_ref` varchar(50) NOT NULL,
  `staff_id` int(50) NOT NULL,
  `status` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(50) NOT NULL,
  `position_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`) VALUES
(1, 'SUT'),
(2, 'INTI');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(50) NOT NULL,
  `section_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_email`, `staff_pass`, `position_id`, `program_id`, `gender`) VALUES
('111111', 'BB', 'bb@gmail.com', '$2y$10$8h9mSjLkTQYRQQlcpQ0l9OkLCu5xJRs/P04TMgfiJu8zb4EEVI00S', 2, 1, 'male'),
('666666', 'Ng', 'ng@gmail.com', '$2y$10$n7hIK1pWqz.p22zQdT/rCeVnFBbpQlVNrjb0j6tuGUf1Bdk2P/PZ2', 2, 1, 'male');

-- --------------------------------------------------------

--
-- Table structure for table `stafftimeschedule`
--

CREATE TABLE `stafftimeschedule` (
  `timeID` int(50) NOT NULL,
  `staffID` varchar(50) NOT NULL,
  `scheduleDate` date NOT NULL,
  `scheduleDay` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `bookAvail` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `modal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stafftimeschedule`
--

INSERT INTO `stafftimeschedule` (`timeID`, `staffID`, `scheduleDate`, `scheduleDay`, `startTime`, `endTime`, `bookAvail`, `modal`) VALUES
(1, '111111', '2024-04-17', 'Monday', '12:30:00', '02:30:00', 'available', 'F2F'),
(2, '111111', '2024-04-23', 'Tuesday', '12:45:00', '13:35:00', 'booked', 'F2F'),
(3, '111111', '2024-04-24', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(4, '111111', '2024-05-01', 'Wednesday', '11:30:00', '00:30:00', 'booked', 'Online'),
(5, '111111', '2024-05-08', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(6, '111111', '2024-05-15', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(7, '666666', '2024-04-23', 'Tuesday', '12:35:00', '12:50:00', 'booked', 'Online'),
(8, '666666', '2024-04-30', 'Tuesday', '12:35:00', '12:50:00', 'available', 'Online'),
(9, '666666', '2024-05-07', 'Tuesday', '12:35:00', '12:50:00', 'available', 'Online');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(50) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_pass` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `program_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `student_email`, `student_pass`, `gender`, `program_id`) VALUES
('333333', 'test', 'test@gmail.com', '$2y$10$cxOi.yMpZQZKuVQki9SBpubXIiBv5Ws3MXKJ6uRsCOk3ZRKIUDVWe', ' male', 1),
('666666', 'ng', 'ng@gmail.com', '$2y$10$YTdMNStEArWZK9OZb8C/SuRRGKDV2w/jFIV.yXvl95auYnzCxuL2q', ' male', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_bookings`
--

CREATE TABLE `student_bookings` (
  `booking_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `modal` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `isRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_bookings`
--

INSERT INTO `student_bookings` (`booking_id`, `student_id`, `staff_id`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `booking_time`, `isRead`) VALUES
(6, 666666, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:39:24', 1),
(7, 666666, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:40:08', 1),
(8, 666666, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:41:15', 1),
(9, 666666, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:42:56', 1),
(10, 666666, 666666, '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:43:06', 1),
(11, 666666, 666666, '2024-05-07', '12:35:00', '12:50:00', 'Online', 'cancelled', '2024-04-13 14:43:28', 1),
(12, 333333, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:56:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(50) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `section_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesession`
--

CREATE TABLE `timesession` (
  `sessionID` int(50) NOT NULL,
  `timeID` int(50) NOT NULL,
  `timeList` varchar(100) NOT NULL,
  `day` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`timeID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `student_bookings`
--
ALTER TABLE `student_bookings`
  ADD PRIMARY KEY (`booking_id`);

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
-- AUTO_INCREMENT for table `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `leave_application_lecturer`
--
ALTER TABLE `leave_application_lecturer`
  MODIFY `leave_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafftimeschedule`
--
ALTER TABLE `stafftimeschedule`
  MODIFY `timeID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_bookings`
--
ALTER TABLE `student_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `position_id` FOREIGN KEY (`position_id`) REFERENCES `position` (`position_id`),
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `program_id` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
