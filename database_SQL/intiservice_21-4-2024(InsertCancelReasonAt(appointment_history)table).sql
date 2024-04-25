-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 05:13 PM
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
  `cancel_reason` text NOT NULL,
  `booking_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_history`
--

INSERT INTO `appointment_history` (`id`, `student_id`, `student_name`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `staff_id`, `cancel_reason`, `booking_timestamp`) VALUES
(20, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'cancelled', 111111, '', '2024-04-13 08:09:46'),
(21, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'booked', 111111, '', '2024-04-13 08:11:29'),
(22, 666666, 'ng', '2024-04-17', '12:30:00', '02:30:00', 'F2F', 'cancelled', 111111, '', '2024-04-13 08:13:19'),
(51, 666666, 'Ng', '2024-05-14', '12:30:00', '01:30:00', 'Online', 'booked', 666666, '', '2024-04-19 03:04:41'),
(52, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 06:45:06'),
(53, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 06:45:30'),
(54, 666666, 'Ng', '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 06:52:39'),
(55, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 06:52:43'),
(56, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 06:52:45'),
(57, 666666, 'Ng', '2024-05-14', '12:30:00', '01:30:00', 'Online', 'cancelled', 666666, '', '2024-04-21 06:53:29'),
(58, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 06:53:33'),
(59, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 06:53:36'),
(60, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 07:04:56'),
(61, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 07:05:00'),
(62, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'Online', 'booked', 666666, '', '2024-04-21 07:05:03'),
(63, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'Online', 'cancelled', 666666, '', '2024-04-21 07:05:06'),
(64, 666666, 'Ng', '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 07:07:20'),
(65, 666666, 'Ng', '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 07:07:25'),
(66, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 07:08:34'),
(67, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 07:08:40'),
(68, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 07:09:08'),
(69, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 07:09:11'),
(70, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 07:09:26'),
(71, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 07:09:35'),
(72, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 07:09:39'),
(73, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 07:10:00'),
(74, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 07:10:04'),
(75, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 07:10:09'),
(76, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 07:15:46'),
(77, 666666, 'Ng', '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 07:15:49'),
(78, 666666, 'Ng', '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'booked', 666666, '', '2024-04-21 07:18:24'),
(79, 666666, 'Ng', '2024-05-07', '12:30:00', '01:30:00', 'Online', 'booked', 666666, '', '2024-04-21 07:18:27'),
(80, 666666, 'Ng', '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, '', '2024-04-21 13:18:06'),
(81, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 13:41:50'),
(82, 666666, 'Ng', '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 13:42:03'),
(83, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 13:48:22'),
(84, 666666, 'Ng', '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 13:54:12'),
(85, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 14:09:52'),
(86, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 14:13:16'),
(87, 666666, 'Ng', '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 14:13:22'),
(88, 666666, 'Ng', '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 14:13:59'),
(89, 666666, 'Ng', '2024-05-07', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '', '2024-04-21 14:28:52'),
(90, 666666, 'Ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', 666666, '', '2024-04-21 14:33:56'),
(91, 666666, 'Ng', '2024-04-30', '12:35:00', '12:50:00', 'Online', 'cancelled', 666666, '', '2024-04-21 14:34:00'),
(92, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 14:35:05'),
(93, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, '', '2024-04-21 14:35:10'),
(94, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 14:44:00'),
(95, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 15:05:03'),
(96, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 15:07:28'),
(97, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, 'dasdasd', '2024-04-21 15:08:38'),
(98, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', 0, '', '2024-04-21 15:10:03'),
(99, 666666, 'Ng', '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', 0, 'Because I try to do a new consultation system!!.', '2024-04-21 15:10:18'),
(100, 666666, 'Ng', '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'cancelled', 666666, 'Whyyyyyyyyyy Supmaheyaaaaaaaaaaaa', '2024-04-21 15:12:56');

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

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `section_name`) VALUES
(1, 'C1'),
(2, 'C2');

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
('000000', 'New', 'new@email.com', '$2y$10$.Uk824pblg82SgRsj5AXJumLQ7f9Yq5vh6GbDIEauesDpMY3KIjgO', 2, 2, 'male'),
('111111', 'BB', 'bb@gmail.com', '$2y$10$8h9mSjLkTQYRQQlcpQ0l9OkLCu5xJRs/P04TMgfiJu8zb4EEVI00S', 2, 1, 'male'),
('555555', 'll', 'll@gmail.com', '$2y$10$7VnsjWHWa4/2fYEOnogOg.tUnyfKnjdQ.VQ6cfCLD0DTCjAQhsEXO', 1, 1, 'male'),
('666666', 'Ng', 'ng@gmail.com', '$2y$10$n7hIK1pWqz.p22zQdT/rCeVnFBbpQlVNrjb0j6tuGUf1Bdk2P/PZ2', 2, 1, 'male'),
('777777', 'kk', 'kk@gmail.com', '$2y$10$4wCzzNuno/TECiU2IQOUP.mlAjYMIel2w3skFwqssRfCRDYs4d/J.', 2, 2, 'male');

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
(2, '111111', '2024-04-23', 'Tuesday', '12:45:00', '13:35:00', 'available', 'F2F'),
(3, '111111', '2024-04-24', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(4, '111111', '2024-05-01', 'Wednesday', '11:30:00', '00:30:00', 'booked', 'Online'),
(5, '111111', '2024-05-08', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(6, '111111', '2024-05-15', 'Wednesday', '11:30:00', '00:30:00', 'available', 'Online'),
(7, '666666', '2024-04-23', 'Tuesday', '12:35:00', '12:50:00', 'available', 'Online'),
(8, '666666', '2024-04-30', 'Tuesday', '12:35:00', '12:50:00', 'available', 'Online'),
(9, '666666', '2024-05-07', 'Tuesday', '12:35:00', '12:50:00', 'booked', 'Online'),
(11, '777777', '2024-04-23', 'Tuesday', '23:00:00', '00:30:00', 'available', 'F2F'),
(12, '777777', '2024-04-30', 'Tuesday', '23:00:00', '00:30:00', 'available', 'F2F'),
(13, '777777', '2024-05-07', 'Tuesday', '23:00:00', '00:30:00', 'booked', 'F2F'),
(14, '777777', '2024-05-14', 'Tuesday', '23:00:00', '00:30:00', 'available', 'F2F'),
(15, '000000', '2024-04-30', 'Tuesday', '12:30:00', '12:55:00', 'available', 'F2F'),
(16, '000000', '2024-05-07', 'Tuesday', '12:30:00', '12:55:00', 'available', 'F2F'),
(17, '000000', '2024-05-14', 'Tuesday', '12:30:00', '12:55:00', 'available', 'F2F'),
(18, '000000', '2024-05-21', 'Tuesday', '12:30:00', '12:55:00', 'available', 'F2F'),
(19, '999999', '2024-04-15', 'Monday', '12:30:00', '01:30:00', 'available', 'F2F'),
(20, '999999', '2024-04-22', 'Monday', '12:30:00', '01:30:00', 'available', 'F2F'),
(21, '999999', '2024-04-29', 'Monday', '12:30:00', '01:30:00', 'available', 'F2F'),
(22, '999999', '2024-05-06', 'Monday', '12:30:00', '01:30:00', 'available', 'F2F'),
(23, '888888', '2024-04-24', 'Wednesday', '02:30:00', '03:30:00', 'available', 'Online'),
(24, '888888', '2024-05-01', 'Wednesday', '02:30:00', '03:30:00', 'available', 'Online'),
(25, '888888', '2024-05-08', 'Wednesday', '02:30:00', '03:30:00', 'available', 'Online'),
(26, '888888', '2024-05-15', 'Wednesday', '02:30:00', '03:30:00', 'available', 'Online'),
(27, '555555', '2024-04-15', 'Monday', '12:30:00', '01:00:00', 'available', 'F2F'),
(28, '555555', '2024-04-22', 'Monday', '12:30:00', '01:00:00', 'available', 'F2F'),
(29, '555555', '2024-04-29', 'Monday', '12:30:00', '01:00:00', 'available', 'F2F'),
(30, '555555', '2024-05-06', 'Monday', '12:30:00', '01:00:00', 'available', 'F2F'),
(31, '666666', '2024-04-23', 'Tuesday', '12:30:00', '01:30:00', 'available', 'F2F'),
(32, '666666', '2024-04-30', 'Tuesday', '12:30:00', '01:30:00', 'available', 'F2F'),
(33, '666666', '2024-05-07', 'Tuesday', '12:30:00', '01:30:00', 'booked', 'F2F'),
(34, '666666', '2024-05-14', 'Tuesday', '12:30:00', '01:30:00', 'available', 'F2F'),
(35, '666666', '2024-04-30', 'Tuesday', '12:30:00', '01:30:00', 'available', 'Online'),
(36, '666666', '2024-05-07', 'Tuesday', '12:30:00', '01:30:00', 'booked', 'Online'),
(37, '666666', '2024-05-14', 'Tuesday', '12:30:00', '01:30:00', 'available', 'Online'),
(38, '666666', '2024-05-21', 'Tuesday', '12:30:00', '01:30:00', 'available', 'Online');

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
('666666', 'Ng', 'ng@gmail.com', '$2y$10$RXLcBl50pviKY7pEuWvh5e4/gdPA9PzgiNzGe0XF8KLzy4OcoHcMC', ' male', 1),
('999999', 'jj', 'jj@gmail.com', '$2y$10$DcHdmhduMq2PsTM78oVwpuTpG1QU5wFT9NgHLgybfA24yb6rSjUdi', ' male', 1);

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
(12, 333333, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-13 14:56:11', 1),
(13, 777777, 666666, '2024-04-23', '12:35:00', '12:50:00', 'Online', 'cancelled', '2024-04-13 15:23:07', 1),
(14, 666666, 777777, '2024-05-07', '23:00:00', '00:30:00', 'F2F', 'booked', '2024-04-14 06:20:15', 0),
(15, 666666, 111111, '2024-05-01', '11:30:00', '00:30:00', 'Online', 'cancelled', '2024-04-14 06:33:17', 0),
(16, 666666, 777777, '2024-05-07', '23:00:00', '00:30:00', 'F2F', 'cancelled', '2024-04-14 06:49:47', 0),
(17, 666666, 777777, '2024-05-07', '23:00:00', '00:30:00', 'F2F', 'booked', '2024-04-14 06:49:52', 0),
(18, 666666, 111111, '2024-05-01', '11:30:00', '00:30:00', 'Online', 'booked', '2024-04-14 09:07:00', 0),
(19, 666666, 111111, '2024-04-23', '12:45:00', '13:35:00', 'F2F', 'cancelled', '2024-04-14 09:07:13', 0),
(20, 666666, 666666, '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-18 14:25:43', 1),
(21, 666666, 666666, '2024-05-14', '12:30:00', '01:30:00', 'Online', 'booked', '2024-04-19 03:04:41', 1),
(22, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 06:45:06', 1),
(23, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 06:45:30', 1),
(24, 666666, 666666, '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 06:52:39', 1),
(25, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 06:52:43', 1),
(26, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 06:52:45', 1),
(27, 666666, 666666, '2024-05-14', '12:30:00', '01:30:00', 'Online', 'cancelled', '2024-04-21 06:53:29', 1),
(28, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 06:53:33', 1),
(29, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 06:53:36', 1),
(30, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 07:04:56', 1),
(31, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 07:05:00', 1),
(32, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'Online', 'booked', '2024-04-21 07:05:03', 1),
(33, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'Online', 'cancelled', '2024-04-21 07:05:06', 1),
(34, 666666, 666666, '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 07:07:20', 1),
(35, 666666, 666666, '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 07:07:25', 1),
(36, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 07:08:34', 0),
(37, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 07:08:40', 0),
(38, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 07:09:08', 0),
(39, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 07:09:11', 0),
(40, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 07:09:26', 0),
(41, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 07:09:35', 0),
(42, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 07:09:39', 0),
(43, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 07:10:00', 0),
(44, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 07:10:04', 0),
(45, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 07:10:09', 0),
(46, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 07:15:46', 1),
(47, 666666, 666666, '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 07:15:49', 1),
(48, 666666, 666666, '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'booked', '2024-04-21 07:18:24', 1),
(49, 666666, 666666, '2024-05-07', '12:30:00', '01:30:00', 'Online', 'booked', '2024-04-21 07:18:27', 1),
(50, 666666, 666666, '2024-04-30', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 13:18:06', 1),
(51, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 13:41:50', 0),
(52, 666666, 0, '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 13:42:03', 0),
(53, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 13:48:22', 0),
(54, 666666, 0, '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 13:54:12', 0),
(55, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 14:09:52', 0),
(56, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 14:13:16', 0),
(57, 666666, 0, '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 14:13:22', 0),
(58, 666666, 0, '2024-05-14', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 14:13:59', 0),
(59, 666666, 666666, '2024-05-07', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-21 14:28:52', 1),
(60, 666666, 666666, '2024-04-30', '12:35:00', '12:50:00', 'Online', 'booked', '2024-04-21 14:33:56', 1),
(61, 666666, 666666, '2024-04-30', '12:35:00', '12:50:00', 'Online', 'cancelled', '2024-04-21 14:34:00', 1),
(62, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 14:35:05', 0),
(63, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 14:35:10', 0),
(64, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 14:44:00', 0),
(65, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 15:05:03', 0),
(66, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 15:07:28', 0),
(67, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 15:08:38', 0),
(68, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'booked', '2024-04-21 15:10:03', 0),
(69, 666666, 0, '2024-05-07', '12:30:00', '12:55:00', 'F2F', 'cancelled', '2024-04-21 15:10:18', 0),
(70, 666666, 666666, '2024-04-23', '12:30:00', '01:30:00', 'F2F', 'cancelled', '2024-04-21 15:12:56', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `student_subject_id` int(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject_id` varchar(50) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `program_id` int(50) NOT NULL,
  `section_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`student_subject_id`, `student_id`, `subject_id`, `staff_id`, `program_id`, `section_id`) VALUES
(2, '666666', 'COM10007', '666666', 1, 1),
(6, '666666', 'SWE20004', '111111', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(50) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `program_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_name`, `program_id`) VALUES
('COM10007', 'Communication Skills', 1),
('SWE20004', 'FYP-B', 1);

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
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`student_subject_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `program_id` (`program_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `leave_application_lecturer`
--
ALTER TABLE `leave_application_lecturer`
  MODIFY `leave_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafftimeschedule`
--
ALTER TABLE `stafftimeschedule`
  MODIFY `timeID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `student_bookings`
--
ALTER TABLE `student_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `student_subject_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`),
  ADD CONSTRAINT `student_subjects_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`),
  ADD CONSTRAINT `student_subjects_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`),
  ADD CONSTRAINT `student_subjects_ibfk_5` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
