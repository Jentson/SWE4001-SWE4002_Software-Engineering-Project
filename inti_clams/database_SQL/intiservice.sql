-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2024 at 07:35 AM
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
  `appointment_history_id` int(11) NOT NULL,
  `time_id` int(50) NOT NULL,
  `student_id` varchar(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `modal` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `booking_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_history`
--

INSERT INTO `appointment_history` (`appointment_history_id`, `time_id`, `student_id`, `student_name`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `staff_id`, `reason`, `booking_timestamp`) VALUES
(1, 3, 'J20033009', 'Ng Zheng Han', '2024-06-13', '11:13:00', '12:13:00', 'Online', 'booked', 'L111111', 'replacement', '2024-06-12 15:13:31'),
(2, 3, 'J20033009', 'Ng Zheng Han', '2024-06-13', '11:13:00', '12:13:00', 'Online', 'completed', 'L111111', '-', '2024-06-12 15:14:02'),
(3, 2, 'J20033009', 'Ng Zheng Han', '2024-06-19', '10:51:00', '11:51:00', 'Online', 'booked', 'L111111', 'da', '2024-06-12 16:36:18'),
(4, 2, 'J20033009', 'Ng Zheng Han', '2024-06-19', '10:51:00', '11:51:00', 'Online', 'cancelled', 'L111111', '55', '2024-06-12 16:36:35'),
(5, 4, 'J20033009', 'Ng Zheng Han', '2024-06-14', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'test', '2024-06-12 16:55:54'),
(6, 4, 'J20033009', 'Ng Zheng Han', '2024-06-14', '10:00:00', '11:00:00', 'F2F', 'cancel_by_staff', 'H222222', 'Byebye', '2024-06-12 16:56:58'),
(7, 5, 'J20033009', 'Ng Zheng Han', '2024-06-21', '10:00:00', '11:00:00', 'Online', 'booked', 'H222222', 'dada', '2024-06-12 17:00:47'),
(8, 5, 'J20033009', 'Ng Zheng Han', '2024-06-21', '10:00:00', '11:00:00', 'Online', 'cancelled', 'H222222', 'tesa', '2024-06-12 17:00:55'),
(9, 6, 'J22036755', 'Rodrikco', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'da', '2024-06-12 17:20:18'),
(10, 6, 'J22036755', 'Rodrikco', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'cancelled', 'H222222', 'da', '2024-06-12 17:20:25'),
(11, 5, 'J22036755', 'Rodrikco', '2024-06-21', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'dad', '2024-06-12 17:20:43'),
(12, 6, 'J20033009', 'Ng Zheng Han', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'test', '2024-06-14 09:27:33'),
(13, 6, 'J22036755', 'Rodrikco', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'cancel_by_staff', 'H222222', 'test', '2024-06-14 09:28:14'),
(14, 7, 'J20033009', 'Ng Zheng Han', '2024-07-05', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'tes', '2024-06-14 17:02:30'),
(15, 8, 'J20033009', 'Ng Zheng Han', '2024-06-16', '10:00:00', '11:00:00', 'Online', 'booked', 'L222222', 'test', '2024-06-14 17:03:35'),
(16, 2, 'J20033009', 'Ng Zheng Han', '2024-06-19', '10:51:00', '11:51:00', 'Online', 'booked', 'L111111', 'Test', '2024-06-15 06:05:01'),
(17, 17, 'J20033009', 'Ng Zheng Han', '2024-06-27', '08:17:00', '10:17:00', 'Online', 'booked', 'H222222', 'replacement', '2024-06-15 06:17:36'),
(18, 18, 'J20033009', 'Ng Zheng Han', '2024-06-24', '10:00:00', '12:00:00', 'Online', 'booked', 'L111111', 'teest', '2024-06-23 16:40:29'),
(20, 20, 'J20033009', 'Ng Zheng Han', '2024-06-24', '08:00:00', '10:00:00', 'F2F', 'booked', 'L111111', 'test_validation', '2024-06-23 17:08:51'),
(56, 18, 'J20033009', 'Ng Zheng Han', '2024-06-24', '10:00:00', '12:00:00', 'Online', 'completed', 'L111111', '-', '2024-06-23 17:43:14'),
(57, 19, 'J20033009', 'Ng Zheng Han', '2024-07-01', '10:00:00', '12:00:00', 'Online', 'booked', 'L111111', 'da', '2024-06-23 18:05:22'),
(58, 19, 'J20033009', 'Ng Zheng Han', '2024-07-01', '10:00:00', '12:00:00', 'Online', 'completed', 'L111111', '-', '2024-06-23 18:14:44'),
(59, 14, 'J20033009', 'Ng Zheng Han', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'booked', 'H222222', 'Test', '2024-06-24 12:12:56'),
(60, 7, 'J20033009', 'Ng Zheng Han', '2024-07-05', '10:00:00', '11:00:00', 'F2F', 'booked', 'H222222', 'Test', '2024-06-24 12:13:13'),
(61, 14, 'J20033009', 'Ng Zheng Han', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'cancelled', 'H222222', 'sa', '2024-06-24 12:15:24'),
(62, 14, 'J20033009', 'Ng Zheng Han', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'booked', 'H222222', 'test', '2024-06-24 12:15:35'),
(63, 16, 'J20033009', 'Ng Zheng Han', '2024-07-13', '09:14:00', '10:16:00', 'Online', 'booked', 'H222222', 'Test', '2024-06-24 17:53:10'),
(64, 15, 'J20033009', 'Ng Zheng Han', '2024-07-06', '09:14:00', '10:16:00', 'Online', 'booked', 'H222222', 'Test', '2024-06-24 17:53:25'),
(65, 15, 'J20033009', 'Ng Zheng Han', '2024-07-06', '09:14:00', '10:16:00', 'Online', 'cancelled', 'H222222', 'Test', '2024-06-24 17:53:39'),
(66, 24, 'J20033009', 'Ng Zheng Han', '2024-07-03', '10:00:00', '12:00:00', 'Online', 'booked', 'H222222', 'test', '2024-06-26 06:12:50'),
(75, 25, 'J20033009', 'Ng Zheng Han', '2024-07-10', '10:00:00', '12:00:00', 'F2F', 'booked', 'H222222', 'hi', '2024-06-26 06:42:56'),
(76, 26, 'J20033009', 'Ng Zheng Han', '2024-07-17', '10:00:00', '12:00:00', 'F2F', 'booked', 'H222222', 'testt', '2024-06-26 06:56:30'),
(77, 10, 'J20033009', 'Ng Zheng Han', '2024-06-30', '10:00:00', '11:00:00', 'Online', 'booked', 'L222222', 'Help me if not I fail', '2024-06-26 07:38:48'),
(78, 27, 'J20033009', 'Ng Zheng Han', '2024-06-29', '08:00:00', '09:00:00', 'Online', 'booked', 'H222222', 'hiihi', '2024-06-26 07:51:36'),
(79, 28, 'J20033009', 'Ng Zheng Han', '2024-07-06', '08:00:00', '09:00:00', 'Online', 'booked', 'H222222', 'Testing ', '2024-06-26 08:04:04'),
(80, 28, 'J20033009', 'Ng Zheng Han', '2024-07-06', '08:00:00', '09:00:00', 'Online', 'cancel_by_staff', 'H222222', 'test', '2024-06-26 08:05:26'),
(81, 29, 'J20033009', 'Ng Zheng Han', '2024-07-13', '08:00:00', '09:00:00', 'Online', 'booked', 'H222222', 'good job', '2024-06-26 08:33:58'),
(82, 24, 'J20033009', 'Ng Zheng Han', '2024-07-03', '10:00:00', '12:00:00', 'Online', 'cancelled', 'H222222', 'i just like', '2024-06-26 08:34:47'),
(83, 22, 'J20033009', 'Ng Zheng Han', '2024-06-27', '14:00:00', '15:00:00', 'Online', 'incomplete', 'L111111', '<sdasd>', '2024-06-26 06:29:02'),
(84, 31, 'J20033009', 'Ng Zheng Han', '2024-07-16', '08:00:00', '10:00:00', 'F2F', 'booked', 'L111111', 'Test', '2024-06-28 23:52:46'),
(85, 31, 'J20033009', 'Ng Zheng Han', '2024-07-16', '08:00:00', '10:00:00', 'F2F', 'cancelled', 'L111111', 'Emergency Case ', '2024-06-29 00:37:47'),
(86, 32, 'J20033009', 'Ng Zheng Han', '2024-07-23', '08:00:00', '10:00:00', 'F2F', 'booked', 'L111111', 'test', '2024-06-29 07:13:24'),
(87, 10, 'J20033009', 'Ng Zheng Han', '2024-06-30', '10:00:00', '11:00:00', 'Online', 'cancel_by_staff', 'L222222', 'Emergency Case', '2024-06-29 17:20:59'),
(88, 32, 'J20033009', 'Ng Zheng Han', '2024-07-23', '08:00:00', '10:00:00', 'F2F', 'cancel_by_staff', 'L111111', '', '2024-06-29 18:13:50');

-- --------------------------------------------------------

--
-- Table structure for table `cancel_stafftime`
--

CREATE TABLE `cancel_stafftime` (
  `cancel_time_id` int(11) NOT NULL,
  `time_id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `modal` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `student_id` varchar(11) NOT NULL,
  `reason` text NOT NULL,
  `cancel_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `isRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancel_stafftime`
--

INSERT INTO `cancel_stafftime` (`cancel_time_id`, `time_id`, `staff_id`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `student_id`, `reason`, `cancel_timestamp`, `isRead`) VALUES
(1, 4, 'H222222', '2024-06-14', '10:00:00', '11:00:00', 'F2F', 'cancel_by_staff', 'J20033009', 'Byebye', '2024-06-12 16:56:58', 1),
(2, 6, 'H222222', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'cancel_by_staff', 'J22036755', 'test', '2024-06-14 09:28:14', 1),
(3, 28, 'H222222', '2024-07-06', '08:00:00', '09:00:00', 'Online', 'cancel_by_staff', 'J20033009', 'test', '2024-06-26 08:05:26', 1),
(4, 10, 'L222222', '2024-06-30', '10:00:00', '11:00:00', 'Online', 'cancel_by_staff', 'J20033009', 'Emergency Case', '2024-06-29 17:20:59', 1),
(5, 32, 'L111111', '2024-07-23', '08:00:00', '10:00:00', 'F2F', 'cancel_by_staff', 'J20033009', '', '2024-06-29 18:13:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(2) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'INTI International University & Colleges'),
(2, 'Swinburne University of Technology'),
(3, 'University of Hertfordshire'),
(4, 'Southern New Hampshire University'),
(5, 'Center of Art and Design'),
(6, 'None Department');

-- --------------------------------------------------------

--
-- Table structure for table `hop_approval`
--

CREATE TABLE `hop_approval` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `hop_id` varchar(50) NOT NULL,
  `process` tinyint(1) NOT NULL,
  `studentIsRead` tinyint(1) NOT NULL DEFAULT 0,
  `staffIsRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hop_approval`
--

INSERT INTO `hop_approval` (`id`, `leave_id`, `hop_id`, `process`, `studentIsRead`, `staffIsRead`) VALUES
(1, 2, 'H222222', 1, 1, 1),
(2, 5, 'H222222', 0, 0, 1),
(3, 3, 'H222222', 1, 1, 1),
(4, 6, 'H222222', 1, 1, 0),
(5, 7, 'H222222', 1, 1, 1),
(6, 9, 'H222222', 1, 1, 1),
(7, 10, 'H222222', 1, 1, 1),
(8, 8, 'H222222', 0, 0, 1),
(9, 14, 'H222222', 1, 1, 1),
(10, 12, 'H222222', 1, 1, 0),
(11, 1, 'H222222', 1, 1, 0),
(12, 16, 'H222222', 1, 1, 0),
(13, 17, 'H222222', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ioav_approval`
--

CREATE TABLE `ioav_approval` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `ioav_id` varchar(50) NOT NULL,
  `process` tinyint(1) NOT NULL,
  `studentIsRead` tinyint(1) NOT NULL DEFAULT 0,
  `staffIsRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_actions`
--

CREATE TABLE `leave_actions` (
  `action_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `action_type` enum('apply','delete') DEFAULT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_actions`
--

INSERT INTO `leave_actions` (`action_id`, `user_id`, `leave_id`, `action_type`, `action_time`) VALUES
(1, 0, 1, 'apply', '2024-06-25 20:21:17'),
(2, 0, 2, 'apply', '2024-06-25 20:21:37'),
(3, 0, 3, 'apply', '2024-06-25 20:22:01'),
(4, 0, 5, 'apply', '2024-06-26 05:55:38'),
(5, 0, 5, 'delete', '2024-06-26 05:56:55'),
(6, 0, 3, 'delete', '2024-06-26 05:59:22'),
(7, 0, 6, 'apply', '2024-06-26 06:05:41'),
(8, 0, 7, 'apply', '2024-06-26 06:39:15'),
(9, 0, 8, 'apply', '2024-06-26 06:45:47'),
(10, 0, 9, 'apply', '2024-06-26 06:52:02'),
(11, 0, 11, 'apply', '2024-06-26 07:01:54'),
(12, 0, 12, 'apply', '2024-06-26 07:11:54'),
(13, 0, 13, 'apply', '2024-06-26 07:36:04'),
(14, 0, 14, 'apply', '2024-06-26 07:43:45'),
(15, 0, 15, 'apply', '2024-06-26 07:58:11'),
(16, 0, 16, 'apply', '2024-06-28 23:38:21'),
(17, 0, 18, 'apply', '2024-06-29 07:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `leave_application`
--

CREATE TABLE `leave_application` (
  `leave_id` int(11) NOT NULL,
  `student_id` varchar(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `state` varchar(20) NOT NULL,
  `subject_id` varchar(15) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `documents` blob NOT NULL,
  `department` int(2) NOT NULL,
  `lecturer_approval` varchar(20) NOT NULL,
  `lecturer_remarks` varchar(50) NOT NULL,
  `ioav_approval` varchar(20) NOT NULL,
  `ioav_remarks` varchar(50) NOT NULL,
  `hop_approval` varchar(20) NOT NULL,
  `hop_remarks` varchar(50) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `notification_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_application`
--

INSERT INTO `leave_application` (`leave_id`, `student_id`, `student_name`, `state`, `subject_id`, `start_date`, `end_date`, `reason`, `documents`, `department`, `lecturer_approval`, `lecturer_remarks`, `ioav_approval`, `ioav_remarks`, `hop_approval`, `hop_remarks`, `is_deleted`, `notification_status`) VALUES
(1, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-06-27', '2024-06-28', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(2, 'J20033009', 'Ng Zheng Han', 'Local', 'GG333', '2024-07-02', '2024-07-03', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(3, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-06-27', '2024-06-28', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 1, 0),
(4, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-06-26', '2024-06-27', 'Testing', '', 2, 'Pending', '', 'Not Required', '', 'Pending', '', 0, 0),
(5, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-06-26', '2024-06-27', 'Testing', '', 2, 'Approved', '', 'Not Required', '', 'Pending', '', 1, 0),
(6, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-06-27', '2024-06-29', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(7, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-06-30', '2024-07-01', 'testing', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(8, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-07-01', '2024-07-02', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Pending', '', 0, 0),
(9, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-07-02', '2024-07-03', 'testing', '', 2, 'Approved', 'hi', 'Not Required', '', 'Approved', '', 0, 0),
(10, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-07-03', '2024-07-04', 'testing', '', 2, 'Approved', 'hi', 'Not Required', '', 'Approved', '', 0, 0),
(11, 'J20033009', 'Ng Zheng Han', 'Local', 'GG333', '2024-07-03', '2024-07-04', 'testing', '', 2, 'Pending', '', 'Not Required', '', 'Pending', '', 0, 0),
(12, 'J20033009 ', 'Ng Zheng Han', 'Local', 'COM10007', '2024-07-10', '2024-07-12', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(13, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-07-17', '2024-07-18', 'test', '', 2, 'Rejected', '', 'Not Required', '', 'Not Required', '', 0, 0),
(14, 'J20033009', 'Ng Zheng Han', 'Local', 'COM10007', '2024-07-06', '2024-07-07', 'test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', 'reason', 0, 0),
(15, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-06-20', '2024-06-21', 'test', '', 2, 'Rejected', '', 'Not Required', '', 'Not Required', '', 0, 0),
(16, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-07-24', '2024-07-25', 'Test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(17, 'J20033009', 'Ng Zheng Han', 'Local', 'GG333', '2024-06-29', '2024-06-29', 'Test', '', 2, 'Approved', '', 'Not Required', '', 'Approved', '', 0, 0),
(18, 'J20033009', 'Ng Zheng Han', 'Local', 'T111111', '2024-06-29', '2024-06-29', 'Test', '', 2, 'Pending', '', 'Not Required', '', 'Pending', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_approval`
--

CREATE TABLE `lecturer_approval` (
  `id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `lecturer_id` varchar(50) NOT NULL,
  `process` tinyint(1) NOT NULL,
  `studentIsRead` tinyint(1) NOT NULL,
  `staffIsRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturer_approval`
--

INSERT INTO `lecturer_approval` (`id`, `leave_id`, `lecturer_id`, `process`, `studentIsRead`, `staffIsRead`) VALUES
(1, 1, 'H222222', 1, 1, 1),
(2, 2, 'L111111', 1, 1, 1),
(3, 3, 'L222222', 1, 1, 1),
(4, 4, 'H222222', 0, 0, 1),
(5, 5, 'L222222', 1, 1, 1),
(6, 6, 'H222222', 1, 1, 1),
(7, 7, 'H222222', 1, 1, 1),
(8, 8, 'L222222', 1, 1, 1),
(9, 9, 'H222222', 1, 1, 1),
(10, 10, 'H222222', 1, 1, 1),
(11, 11, 'L111111', 0, 0, 1),
(12, 12, 'H222222', 1, 1, 1),
(13, 13, 'L222222', 1, 1, 0),
(14, 14, 'H222222', 1, 1, 1),
(15, 15, 'L222222', 1, 1, 1),
(16, 16, 'L222222', 1, 1, 1),
(17, 17, 'L111111', 1, 1, 1),
(18, 18, 'L222222', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `student_id`, `token`, `expiry`) VALUES
(2, 'J20033009', 'febe119dc065d66de46f068468a5d642a32ce691630988f5bd4db53896fc24c6', '2024-06-03 23:42:01'),
(4, 'J20033009', '5ba266c1ea74537b2af15ba8a56f43bd3e3bcd0b1a815bf0a9f7f45706a39e0a', '2024-06-04 13:49:48'),
(5, 'J20033009', 'a247e7d17110de45b9a8596aa72a8bb9c550243d5e59bc787c0c53cb3a90e393', '2024-06-10 10:16:54'),
(6, 'J20033009', '13425f9331e1311d2727ce0b3eca0f237fd73226fa26568936c587250d58c344', '2024-06-10 10:17:38'),
(7, 'J20033009', '7b94a10c0dd5afe632e4dafd2069687ecb2974e8916529be80c83ce8066bb0e6', '2024-06-13 01:12:40'),
(8, 'J20033009', '9e282f133753ee44e68f88eec6e15d2ba1b427062f33e41a0d7777a3f07fbcef', '2024-06-13 01:14:28'),
(9, 'J20033009', 'b8f8e4cd5dbb230712745e561133e9fd4a0d4cb6510263ba2cbd0553a629c511', '2024-06-13 01:19:37'),
(10, 'J20033009', '3d5dba27a828011ae20cb99332eafe236811db633e9e6122c9f667619ec9f22f', '2024-06-13 01:22:04'),
(11, 'J20033009', 'b7b59bc87edbb28906e5ca1168fd3c1d915ecb8b0a7727976dc99d3fa1b1347f', '2024-06-13 01:26:02'),
(12, 'J20033009', 'fcc42164b8b908d0be2b5d04425b1c955fcc09adf09bc15149435d34819dfa90', '2024-06-13 01:28:39'),
(19, 'J20033009', '73ec562bbe3c4199789fece66016eced76e25c3b4683252a661ae3248d380e10', '2024-06-30 01:30:58'),
(23, 'J20033009', 'a85c75910101d29ffa89fc1a73b128bfa0c112b45008af841b7739cdba7957e6', '2024-07-05 03:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `position_id` int(2) NOT NULL,
  `position_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position_id`, `position_name`) VALUES
(1, 'HOP'),
(2, 'Lecturer'),
(3, 'IOAV'),
(4, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`, `department_id`) VALUES
(1, 'BCSSUT-(Major in CyberSecuiry)', 2),
(2, 'BCSSUT-(Major in DataScience)', 2),
(3, 'BCSSUT-(Major in SoftwareDevelopment)', 2),
(4, 'DCS-(Major in CyberSecuiry)', 1),
(5, 'DCS-(Major in DataScience)', 1),
(6, 'DCS-(Major in CloudComputing)', 1),
(7, 'TestingForCAD', 5),
(8, 'TestingForSNHU', 4),
(9, 'TestingForUOH', 3);

-- --------------------------------------------------------

--
-- Table structure for table `service_department`
--

CREATE TABLE `service_department` (
  `service_id` int(1) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `staff_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_department`
--

INSERT INTO `service_department` (`service_id`, `service_name`, `staff_id`) VALUES
(1, 'PTPTN Service', 'H222222'),
(2, 'Scholarship Service', 'H555555');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` varchar(50) NOT NULL,
  `staff_name` varchar(255) NOT NULL,
  `staff_email` varchar(255) NOT NULL,
  `staff_pass` varchar(255) NOT NULL,
  `staff_identity_number` varchar(20) NOT NULL,
  `staff_address` varchar(255) NOT NULL,
  `phone_number` int(15) NOT NULL,
  `position_id` int(50) NOT NULL,
  `department_id` int(2) NOT NULL,
  `gender` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_email`, `staff_pass`, `staff_identity_number`, `staff_address`, `phone_number`, `position_id`, `department_id`, `gender`) VALUES
('000000', 'admin', 'adc@gmail.com', '$2y$10$1ZXxyyh2yHgDDb48GzCl/u4DO3/DcmJ0aEP4ynxr1QZeoFbvFZdsq', '666666666666', '6houses', 2147483647, 4, 2, 'Male'),
('111111', 'Test', 'j20033009@student.newinti.edu.my', '$2y$10$6eQ.yKekZ.8lt0je/opniuEXCVGPSazF3337PQA3phgDHF1jPP6De', '111111', '111111', 111111, 2, 2, 'Male'),
('H111111', 'Fiona Miller(H1)', 'j20033009@student.newinti.edu.my', '$2y$10$pxKSSrTBOUcdvku4YgbeIOL.z2wPw3pnjhOJhFz7oyR.iZD2DWfiu', '679000000000', 'Address 6', 1234567890, 1, 1, 'Female'),
('H222222', 'George Moore(H2)', 'j20033009@student.newinti.edu.my', '$2y$10$.Pv0wWuiSh0D8fz.R0bJIu4Dthj/1YljTkDJ2v1SVf8pT6P70D.p2', '789000000000', 'Address 7', 1234567890, 1, 2, 'Male'),
('H333333', 'Hannah Taylor(H3)', 'j20033009@student.newinti.edu.my', '$2y$10$8inRAVpjbJtJuocwsRJ8LO0RPe2paOwhrQpdcuoOodTlyifHUX1SS', '890000000000', 'Address 8', 1234567890, 1, 3, 'Female'),
('H444444', 'Ivan Anderson(H4)', 'j20033009@student.newinti.edu.my', '$2y$10$i4UYDW.DARnl.UQcesyK3u7aL27PG7bRx1.nCjnieD9Jj77rhlnGS', '901000000000', 'Address 9', 1234567890, 1, 4, 'Male'),
('H555555', 'Julia Thomas(H5)', 'j20033009@student.newinti.edu.my', '$2y$10$q3sbbIpaS3kfHkQXgIg9Z.NlLOODuibg54.fJrg.S9St6ArbVIs4y', '12345678901', 'Address 10', 1234567890, 1, 5, 'Female'),
('I111111', 'InternationalStaff(I1)', 'j20033009@student.newinti.edu.my', '$2y$10$CQprWfmEQNSsxMl62jSe8ux0kVLRzU.lEIQe7UmuGpb3yfBgPIsBa', 'I111111', 'I111111', 11111111, 3, 6, 'Male'),
('L111111', 'Alice Smith(L1)', 'j20033009@student.newinti.edu.my', '$2y$10$g5HGWlDY.SLyzrUWpg8ZaOAQOveNTBQhfsbHf.wKG2zP0bmXDLj0K', '123000000000', 'Address 12', 1234567892, 2, 1, 'Male'),
('L222222', 'Bob Johnson(L2)', 'j20033009@student.newinti.edu.my', '$2y$10$OOZ545xbcAoz0nZUcrX7n.h/9eRjYPdwkScLKGC.rkeAUfkF282Um', '235000000000', 'Address 2', 1234567890, 2, 2, 'Female'),
('L333333', 'Charlie Brown(L3)', 'j20033009@student.newinti.edu.my', '$2y$10$nZ3VQKfCTXJjSUcPcXf8qe9mS/kOmp0P6XOvw1yNYassxNkSvC/BS', '346000000000', 'Address 3', 1234567890, 2, 3, 'Male'),
('L444444', 'David Wilson(L4)', 'j20033009@student.newinti.edu.my', '$2y$10$nKwFMITbo07ziZexvxMHL.Ndysw6mBngRwT0wyzzoLp5nLdJJofDi', '457000000000', 'Address 4', 1234567890, 2, 4, 'Female'),
('L555555', 'Eve Davis(L5)', 'j20033009@student.newinti.edu.my', '$2y$10$f.wuLMCfHy5hgmw9S0Tyc.VVGygoQmxeLF1sI1l1JoSWn1h/u9Bv.', '568000000000', 'Address 5', 1234567890, 2, 5, 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `staff_timeschedule`
--

CREATE TABLE `staff_timeschedule` (
  `time_id` int(50) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_day` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `book_avail` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `modal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_timeschedule`
--

INSERT INTO `staff_timeschedule` (`time_id`, `staff_id`, `schedule_date`, `schedule_day`, `start_time`, `end_time`, `book_avail`, `modal`) VALUES
(2, 'L111111', '2024-06-19', 'Wednesday', '10:51:00', '11:51:00', 'incomplete', 'Online'),
(4, 'H222222', '2024-06-14', 'Friday', '10:00:00', '11:00:00', 'cancel_by_staff', 'Both'),
(5, 'H222222', '2024-06-21', 'Friday', '10:00:00', '11:00:00', 'incomplete', 'Both'),
(6, 'H222222', '2024-06-28', 'Friday', '10:00:00', '11:00:00', 'cancel_by_staff', 'Both'),
(7, 'H222222', '2024-07-05', 'Friday', '10:00:00', '11:00:00', 'incomplete', 'Both'),
(8, 'L222222', '2024-06-16', 'Sunday', '10:00:00', '11:00:00', 'incomplete', 'Online'),
(9, 'L222222', '2024-06-23', 'Sunday', '10:00:00', '11:00:00', 'expired', 'Online'),
(10, 'L222222', '2024-06-30', 'Sunday', '10:00:00', '11:00:00', 'cancel_by_staff', 'Online'),
(11, 'L222222', '2024-07-07', 'Sunday', '10:00:00', '11:00:00', 'available', 'Online'),
(12, 'H222222', '2024-06-15', 'Saturday', '09:14:00', '10:16:00', 'expired', 'Online'),
(13, 'H222222', '2024-06-22', 'Saturday', '09:14:00', '10:16:00', 'expired', 'Online'),
(14, 'H222222', '2024-06-29', 'Saturday', '09:14:00', '10:16:00', 'incomplete', 'Online'),
(15, 'H222222', '2024-07-06', 'Saturday', '09:14:00', '10:16:00', 'incomplete', 'Online'),
(16, 'H222222', '2024-07-13', 'Saturday', '09:14:00', '10:16:00', 'booked', 'Online'),
(17, 'H222222', '2024-06-27', 'Thursday', '08:17:00', '10:17:00', 'incomplete', 'Online'),
(18, 'L111111', '2024-06-24', 'Monday', '10:00:00', '12:00:00', 'completed', 'Online'),
(19, 'L111111', '2024-07-01', 'Monday', '10:00:00', '12:00:00', 'completed', 'Online'),
(20, 'L111111', '2024-06-24', 'Monday', '08:00:00', '10:00:00', 'incomplete', 'F2F'),
(21, 'L111111', '2024-06-24', 'Monday', '12:00:00', '14:00:00', 'expired', 'Online'),
(22, 'L111111', '2024-06-27', 'Thursday', '14:00:00', '15:00:00', 'incomplete', 'Online'),
(23, 'H222222', '2024-06-26', 'Wednesday', '10:00:00', '12:00:00', 'expired', 'Both'),
(24, 'H222222', '2024-07-03', 'Wednesday', '10:00:00', '12:00:00', 'expired', 'Both'),
(25, 'H222222', '2024-07-10', 'Wednesday', '10:00:00', '12:00:00', 'booked', 'Both'),
(26, 'H222222', '2024-07-17', 'Wednesday', '10:00:00', '12:00:00', 'booked', 'Both'),
(27, 'H222222', '2024-06-29', 'Saturday', '08:00:00', '09:00:00', 'incomplete', 'Online'),
(28, 'H222222', '2024-07-06', 'Saturday', '08:00:00', '09:00:00', 'cancel_by_staff', 'Online'),
(29, 'H222222', '2024-07-13', 'Saturday', '08:00:00', '09:00:00', 'booked', 'Online'),
(30, 'H222222', '2024-07-20', 'Saturday', '08:00:00', '09:00:00', 'available', 'Online'),
(31, 'L111111', '2024-07-16', 'Tuesday', '08:00:00', '10:00:00', 'available', 'Both'),
(32, 'L111111', '2024-07-23', 'Tuesday', '08:00:00', '10:00:00', 'cancel_by_staff', 'Both'),
(33, 'L111111', '2024-07-30', 'Tuesday', '08:00:00', '10:00:00', 'available', 'Both'),
(34, 'L111111', '2024-08-06', 'Tuesday', '08:00:00', '10:00:00', 'available', 'Both'),
(35, 'L111111', '2024-08-13', 'Tuesday', '08:00:00', '10:00:00', 'available', 'Both');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Rejected'),
(4, 'Withdraw'),
(5, 'Graduated'),
(6, 'Freezed');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_pass` varchar(255) NOT NULL,
  `student_phone_number` int(15) NOT NULL,
  `student_identify_number` varchar(20) NOT NULL,
  `student_address` varchar(255) NOT NULL,
  `state` varchar(20) NOT NULL,
  `department_id` int(2) NOT NULL,
  `program_id` int(11) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `student_email`, `student_pass`, `student_phone_number`, `student_identify_number`, `student_address`, `state`, `department_id`, `program_id`, `gender`, `status_id`) VALUES
('J111111', 'TestingForSNHU', 'TestingForSNHU@newinti.edu.my', '$2y$10$yCiSO222YiEwSuQYrpN9ce2iZAS9dIyuwZqZ3KLEc/1OW9DUOrE3a', 111111, '111111', '111111', 'International', 4, 8, 'Male', 2),
('J12344432', 'Ambank', 'j12344432@student.newinti.edu.my', '$2y$10$F0avw7P6PFH9DoXiJd19WeCrgFMFGyyZtXPUDguSR.xkZctMWsO3.', 11, '111', 'Qqq', 'Local', 2, 1, ' Male', 2),
('J20032658', 'Ng Chin Shen', 'j20032658@newinti.edu.my', '$2y$10$A7ZxdSaDLAodRa0SA1Dnu.4RVgQIoPNIoOdCZeVc8g7dT2hUUmPW2', 123, '123', '123', 'Local', 1, 4, ' Male', 2),
('J20033009', 'Ng Zheng Han', 'j20033009@student.newinti.edu.my', '$2y$10$Jq8Dk2He9q6ED1Rqav5QX.yoFH6Pvd0WRlarJ2cVp58RXfiPSvIym', 111111111, '666666', 'This is secrect', 'Local', 2, 1, 'Male', 2),
('J20044697', 'TestStudent', 'j20044697@student.newinti.edu.my', '$2y$10$pH/G5zkp1/ogPQkVQWycXeTgYXQwglALajTkY.SRjkLbUz7n8SwfK', 1115455545, '01554566328', 'Secrect', 'Local', 2, 1, ' Male', 2),
('J22036755', 'Rodrikco', 'j22036755@newinti.edu.my', '$2y$10$kKdkKx3XOHFi7SYhWCSD/eCEkVzUWbnnZVJcRNbcxC3wMWlzhzI72', 666666, '666666', '123', 'International', 3, 9, ' Male', 2),
('J222222', 'TestingForCAD', 'TestingForCAD@newinti.edu.my', '$2y$10$Bf4KUCKgy4OpI0meCGMHe.OwQofeX8yapqGt25MUVJByCiuRvBWgK', 222222, '222222', '222222', 'International', 5, 7, ' Male', 2),
('J999999', 'TestAccount', 'j999999@student.newinti.edu.my', '$2y$10$0MKDvlvwezwTXFPfgkLHX.uM2a6No291L9q7VsFhqPTI7HjroXHqm', 123456789, '0123456789', '0123456789', 'Local', 2, 1, ' Male', 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_bookings`
--

CREATE TABLE `student_bookings` (
  `booking_id` int(11) NOT NULL,
  `student_id` varchar(11) DEFAULT NULL,
  `staff_id` varchar(20) DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `modal` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_id` int(50) NOT NULL,
  `isRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_bookings`
--

INSERT INTO `student_bookings` (`booking_id`, `student_id`, `staff_id`, `schedule_date`, `start_time`, `end_time`, `modal`, `status`, `reason`, `booking_time`, `time_id`, `isRead`) VALUES
(7, 'J22036755', 'H222222', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'booked', 'da', '2024-06-12 17:20:18', 6, 1),
(8, 'J22036755', 'H222222', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'cancelled', 'da', '2024-06-12 17:20:25', 6, 1),
(9, 'J22036755', 'H222222', '2024-06-21', '10:00:00', '11:00:00', 'F2F', 'incomplete', 'dad', '2024-06-12 17:20:43', 5, 1),
(10, 'J20033009', 'H222222', '2024-06-28', '10:00:00', '11:00:00', 'F2F', 'booked', 'test', '2024-06-14 09:27:33', 6, 1),
(11, 'J20033009', 'H222222', '2024-07-05', '10:00:00', '11:00:00', 'F2F', 'booked', 'tes', '2024-06-14 17:02:30', 7, 1),
(12, 'J20033009', 'L222222', '2024-06-16', '10:00:00', '11:00:00', 'Online', 'incomplete', 'test', '2024-06-14 17:03:35', 8, 1),
(13, 'J20033009', 'L111111', '2024-06-19', '10:51:00', '11:51:00', 'Online', 'incomplete', 'Test', '2024-06-15 06:05:01', 2, 1),
(14, 'J20033009', 'H222222', '2024-06-27', '08:17:00', '10:17:00', 'Online', 'incomplete', 'replacement', '2024-06-15 06:17:36', 17, 1),
(15, 'J20033009', 'L111111', '2024-06-24', '10:00:00', '12:00:00', 'Online', 'completed', 'teest', '2024-06-23 16:40:29', 18, 1),
(17, 'J20033009', 'L111111', '2024-06-24', '08:00:00', '10:00:00', 'F2F', 'incomplete', 'test_validation', '2024-06-23 17:08:51', 20, 1),
(18, 'J20033009', 'L111111', '2024-07-01', '10:00:00', '12:00:00', 'Online', 'completed', 'da', '2024-06-23 18:05:22', 19, 1),
(19, 'J20033009', 'H222222', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'incomplete', 'Test', '2024-06-24 12:12:56', 14, 1),
(20, 'J20033009', 'H222222', '2024-07-05', '10:00:00', '11:00:00', 'F2F', 'booked', 'Test', '2024-06-24 12:13:13', 7, 1),
(21, 'J20033009', 'H222222', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'cancelled', 'sa', '2024-06-24 12:15:24', 14, 1),
(22, 'J20033009', 'H222222', '2024-06-29', '09:14:00', '10:16:00', 'Online', 'incomplete', 'test', '2024-06-24 12:15:35', 14, 1),
(23, 'J20033009', 'H222222', '2024-07-13', '09:14:00', '10:16:00', 'Online', 'booked', 'Test', '2024-06-24 17:53:10', 16, 1),
(24, 'J20033009', 'H222222', '2024-07-06', '09:14:00', '10:16:00', 'Online', 'incomplete', 'Test', '2024-06-24 17:53:25', 15, 1),
(25, 'J20033009', 'H222222', '2024-07-06', '09:14:00', '10:16:00', 'Online', 'cancelled', 'Test', '2024-06-24 17:53:39', 15, 1),
(26, 'J20033009', 'H222222', '2024-07-03', '10:00:00', '12:00:00', 'Online', 'booked', 'test', '2024-06-26 06:12:50', 24, 1),
(34, 'J20033009', 'H222222', '2024-07-05', '10:00:00', '11:00:00', 'F2F', 'incomplete', 'dddq', '2024-06-26 06:35:25', 7, 1),
(35, 'J20033009', 'H222222', '2024-07-10', '10:00:00', '12:00:00', 'F2F', 'booked', 'hi', '2024-06-26 06:42:56', 25, 1),
(36, 'J20033009', 'H222222', '2024-07-17', '10:00:00', '12:00:00', 'F2F', 'booked', 'testt', '2024-06-26 06:56:30', 26, 1),
(37, 'J20033009', 'L222222', '2024-06-30', '10:00:00', '11:00:00', 'Online', 'booked', 'Help me if not I fail', '2024-06-26 07:38:48', 10, 1),
(38, 'J20033009', 'H222222', '2024-06-29', '08:00:00', '09:00:00', 'Online', 'incomplete', 'hiihi', '2024-06-26 07:51:36', 27, 1),
(39, 'J20033009', 'H222222', '2024-07-06', '08:00:00', '09:00:00', 'Online', 'booked', 'Testing ', '2024-06-26 08:04:04', 28, 1),
(40, 'J20033009', 'H222222', '2024-07-13', '08:00:00', '09:00:00', 'Online', 'booked', 'good job', '2024-06-26 08:33:58', 29, 1),
(41, 'J20033009', 'H222222', '2024-07-03', '10:00:00', '12:00:00', 'Online', 'cancelled', 'i just like', '2024-06-26 08:34:47', 24, 1),
(42, 'J20033009', 'L111111', '2024-07-16', '08:00:00', '10:00:00', 'F2F', 'booked', 'Test', '2024-06-28 23:52:46', 31, 1),
(43, 'J20033009', 'L111111', '2024-07-16', '08:00:00', '10:00:00', 'F2F', 'cancelled', 'Emergency Case ', '2024-06-29 00:37:47', 31, 1),
(44, 'J20033009', 'L111111', '2024-07-23', '08:00:00', '10:00:00', 'F2F', 'booked', 'test', '2024-06-29 07:13:24', 32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `student_subject_id` int(50) NOT NULL,
  `student_id` varchar(11) NOT NULL,
  `subject_id` varchar(15) NOT NULL,
  `program_id` int(11) NOT NULL,
  `session` varchar(20) NOT NULL,
  `semester` int(2) NOT NULL,
  `section_id` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`student_subject_id`, `student_id`, `subject_id`, `program_id`, `session`, `semester`, `section_id`) VALUES
(2, 'J20033009', 'COM10007', 1, 'FEB2025', 5, 'C1'),
(4, 'J20033009', 'GG333', 1, 'FEB2025', 5, 'C1'),
(5, 'J22036755', 'TNE30002', 9, 'FEB2025', 4, 'C1'),
(8, 'J20033009', 'T111111', 1, 'FEB2024', 5, 'C1'),
(9, 'J22036755', 'TL111111', 9, 'FEB2024', 4, 'C1'),
(10, 'J22036755', 'TL111112', 9, 'FEB2024', 4, 'C2');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(15) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `department_id` int(2) NOT NULL,
  `staff_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_name`, `department_id`, `staff_id`) VALUES
('COM10007', 'Introduction to Programing', 2, 'H222222'),
('GG333', 'TestProgram', 1, 'L111111'),
('GG5555', 'Subject Test', 3, 'H333333'),
('MPU1111', 'TestProgram', 1, 'L111111'),
('T111111', 'TestProgram1', 2, 'L222222'),
('T222222', 'TestProgram2', 1, 'L111111'),
('T333333', 'TestProgram3', 2, 'L222222'),
('TES3002', 'AAA', 3, 'H333333'),
('TL111111', 'TestL1', 1, 'L111111'),
('TL111112', 'TestL12', 1, 'L111111'),
('TNE30002', 'Networking', 2, 'L555555');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`appointment_history_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `time_id` (`time_id`);

--
-- Indexes for table `cancel_stafftime`
--
ALTER TABLE `cancel_stafftime`
  ADD PRIMARY KEY (`cancel_time_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `time_id` (`time_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `hop_approval`
--
ALTER TABLE `hop_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_id` (`leave_id`);

--
-- Indexes for table `ioav_approval`
--
ALTER TABLE `ioav_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_id` (`leave_id`);

--
-- Indexes for table `leave_actions`
--
ALTER TABLE `leave_actions`
  ADD PRIMARY KEY (`action_id`);

--
-- Indexes for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `lecturer_approval`
--
ALTER TABLE `lecturer_approval`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `service_department`
--
ALTER TABLE `service_department`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `staff_timeschedule`
--
ALTER TABLE `staff_timeschedule`
  ADD PRIMARY KEY (`time_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `student_bookings`
--
ALTER TABLE `student_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `time_id` (`time_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`student_subject_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `appointment_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `cancel_stafftime`
--
ALTER TABLE `cancel_stafftime`
  MODIFY `cancel_time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hop_approval`
--
ALTER TABLE `hop_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ioav_approval`
--
ALTER TABLE `ioav_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_actions`
--
ALTER TABLE `leave_actions`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `leave_application`
--
ALTER TABLE `leave_application`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `lecturer_approval`
--
ALTER TABLE `lecturer_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `position_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service_department`
--
ALTER TABLE `service_department`
  MODIFY `service_id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_timeschedule`
--
ALTER TABLE `staff_timeschedule`
  MODIFY `time_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `student_bookings`
--
ALTER TABLE `student_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `student_subject_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hop_approval`
--
ALTER TABLE `hop_approval`
  ADD CONSTRAINT `hop_approval_ibfk_1` FOREIGN KEY (`leave_id`) REFERENCES `leave_application` (`leave_id`);

--
-- Constraints for table `ioav_approval`
--
ALTER TABLE `ioav_approval`
  ADD CONSTRAINT `ioav_approval_ibfk_1` FOREIGN KEY (`leave_id`) REFERENCES `leave_application` (`leave_id`);

--
-- Constraints for table `leave_application`
--
ALTER TABLE `leave_application`
  ADD CONSTRAINT `leave_application_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `leave_application_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `service_department`
--
ALTER TABLE `service_department`
  ADD CONSTRAINT `service_department_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`position_id`);

--
-- Constraints for table `staff_timeschedule`
--
ALTER TABLE `staff_timeschedule`
  ADD CONSTRAINT `staff_timeschedule_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `student_bookings`
--
ALTER TABLE `student_bookings`
  ADD CONSTRAINT `student_bookings_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `student_bookings_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `student_bookings_ibfk_3` FOREIGN KEY (`time_id`) REFERENCES `staff_timeschedule` (`time_id`);

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `student_subjects_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `subject_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
