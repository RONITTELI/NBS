-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2025 at 06:05 PM
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
-- Database: `nbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'New',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `student_id`, `subject`, `description`, `category`, `status`, `is_anonymous`, `created_at`) VALUES
(1, 3, 'test 1 ', 'test 1 ', 'Other', 'Resolved', 0, '2025-09-04 19:25:38'),
(2, NULL, 'test 2', 'test 2 ', 'Teachers', 'New', 1, '2025-09-04 19:26:19'),
(3, NULL, 'test 3', 'sdfdgs gs g', 'Other', 'Resolved', 1, '2025-09-12 09:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `notice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `description`, `notice_id`) VALUES
(39, 'New Year\'s Day', '2025-01-01', 'Celebration of the beginning of the new year.', NULL),
(40, 'Makar Sankranti / Pongal', '2025-01-14', 'Harvest festival celebrated across India.', NULL),
(41, 'Republic Day', '2025-01-26', 'Commemorates the adoption of the Indian Constitution.', NULL),
(42, 'Vasant Panchami', '2025-02-02', 'Festival dedicated to Goddess Saraswati.', NULL),
(43, 'Maha Shivaratri', '2025-02-26', 'Festival dedicated to Lord Shiva.', NULL),
(44, 'Holi', '2025-03-14', 'Festival of colors celebrated across India.', NULL),
(45, 'Eid al-Fitr', '2025-03-30', 'Marks the end of Ramadan fasting.', NULL),
(46, 'Ram Navami', '2025-04-06', 'Birthday of Lord Rama.', NULL),
(47, 'Mahavir Jayanti', '2025-04-10', 'Celebrates the birth of Lord Mahavira.', NULL),
(48, 'Good Friday', '2025-04-18', 'Christian holiday commemorating the crucifixion of Jesus Christ.', NULL),
(49, 'Hanuman Jayanti', '2025-04-19', 'Birthday of Lord Hanuman.', NULL),
(50, 'Buddha Purnima', '2025-05-12', 'Celebrates the birth of Lord Buddha.', NULL),
(51, 'Eid al-Adha (Bakrid)', '2025-05-07', 'Festival of sacrifice celebrated by Muslims.', NULL),
(52, 'Rath Yatra', '2025-06-27', 'Chariot festival of Lord Jagannath.', NULL),
(53, 'Muharram / Islamic New Year', '2025-07-27', 'Marks the beginning of Islamic calendar year.', NULL),
(54, 'Guru Purnima', '2025-07-10', 'Day to honor teachers and spiritual gurus.', NULL),
(55, 'Raksha Bandhan', '2025-08-09', 'Celebration of the bond between brothers and sisters.', NULL),
(56, 'Independence Day', '2025-08-15', 'National holiday celebrated across India.', NULL),
(57, 'Janmashtami', '2025-08-16', 'Birthday of Lord Krishna.', NULL),
(58, 'Ganesh Chaturthi', '2025-08-27', 'Festival celebrating Lord Ganesha.', NULL),
(59, 'Onam', '2025-09-05', 'Harvest festival celebrated in Kerala.', NULL),
(60, 'Milad-un-Nabi (Id-e-Milad)', '2025-09-05', 'Birth anniversary of Prophet Muhammad.', NULL),
(61, 'Teachers\' Day', '2025-09-05', 'Honoring teachers across India.', NULL),
(62, 'Vishwakarma Puja', '2025-09-17', 'Festival dedicated to Lord Vishwakarma.', NULL),
(63, 'Gandhi Jayanti', '2025-10-02', 'Birthday of Mahatma Gandhi.', NULL),
(64, 'Dussehra / Vijayadashami', '2025-10-02', 'Victory of good over evil.', NULL),
(65, 'Karva Chauth', '2025-10-17', 'Fasting festival for marital prosperity.', NULL),
(66, 'Diwali', '2025-10-29', 'Festival of lights celebrated across India.', NULL),
(67, 'Bhai Dooj', '2025-11-01', 'Festival celebrating sibling bonds.', NULL),
(68, 'Chhath Puja', '2025-11-08', 'Festival dedicated to the Sun God.', NULL),
(69, 'Guru Nanak Jayanti', '2025-11-05', 'Birth anniversary of Guru Nanak.', NULL),
(70, 'Christmas', '2025-12-25', 'Christmas celebration and holiday.', NULL),
(71, 'New Year\'s Eve', '2025-12-31', 'Celebration to welcome the new year.', NULL),
(72, 'New Year\'s Day', '2025-01-01', 'Celebration of the beginning of the new year.', NULL),
(73, 'Makar Sankranti / Pongal', '2025-01-14', 'Harvest festival celebrated across India.', NULL),
(74, 'Republic Day', '2025-01-26', 'Commemorates the adoption of the Indian Constitution.', NULL),
(75, 'Vasant Panchami', '2025-02-02', 'Festival dedicated to Goddess Saraswati.', NULL),
(76, 'Maha Shivaratri', '2025-02-26', 'Festival dedicated to Lord Shiva.', NULL),
(77, 'Holi', '2025-03-14', 'Festival of colors celebrated across India.', NULL),
(78, 'Eid al-Fitr', '2025-03-30', 'Marks the end of Ramadan fasting.', NULL),
(79, 'Ram Navami', '2025-04-06', 'Birthday of Lord Rama.', NULL),
(80, 'Mahavir Jayanti', '2025-04-10', 'Celebrates the birth of Lord Mahavira.', NULL),
(81, 'Good Friday', '2025-04-18', 'Christian holiday commemorating the crucifixion of Jesus Christ.', NULL),
(82, 'Hanuman Jayanti', '2025-04-19', 'Birthday of Lord Hanuman.', NULL),
(83, 'Buddha Purnima', '2025-05-12', 'Celebrates the birth of Lord Buddha.', NULL),
(84, 'Eid al-Adha (Bakrid)', '2025-05-07', 'Festival of sacrifice celebrated by Muslims.', NULL),
(85, 'Rath Yatra', '2025-06-27', 'Chariot festival of Lord Jagannath.', NULL),
(86, 'Muharram / Islamic New Year', '2025-07-27', 'Marks the beginning of Islamic calendar year.', NULL),
(87, 'Guru Purnima', '2025-07-10', 'Day to honor teachers and spiritual gurus.', NULL),
(88, 'Raksha Bandhan', '2025-08-09', 'Celebration of the bond between brothers and sisters.', NULL),
(89, 'Independence Day', '2025-08-15', 'National holiday celebrated across India.', NULL),
(90, 'Janmashtami', '2025-08-16', 'Birthday of Lord Krishna.', NULL),
(91, 'Ganesh Chaturthi', '2025-08-27', 'Festival celebrating Lord Ganesha.', NULL),
(92, 'Onam', '2025-09-05', 'Harvest festival celebrated in Kerala.', NULL),
(93, 'Milad-un-Nabi (Id-e-Milad)', '2025-09-05', 'Birth anniversary of Prophet Muhammad.', NULL),
(94, 'Teachers\' Day', '2025-09-05', 'Honoring teachers across India.', NULL),
(95, 'Vishwakarma Puja', '2025-09-17', 'Festival dedicated to Lord Vishwakarma.', NULL),
(96, 'Gandhi Jayanti', '2025-10-02', 'Birthday of Mahatma Gandhi.', NULL),
(97, 'Dussehra / Vijayadashami', '2025-10-02', 'Victory of good over evil.', NULL),
(98, 'Karva Chauth', '2025-10-17', 'Fasting festival for marital prosperity.', NULL),
(99, 'Diwali', '2025-10-29', 'Festival of lights celebrated across India.', NULL),
(100, 'Bhai Dooj', '2025-11-01', 'Festival celebrating sibling bonds.', NULL),
(101, 'Chhath Puja', '2025-11-08', 'Festival dedicated to the Sun God.', NULL),
(102, 'Guru Nanak Jayanti', '2025-11-05', 'Birth anniversary of Guru Nanak.', NULL),
(103, 'Christmas', '2025-12-25', 'Christmas celebration and holiday.', NULL),
(104, 'New Year\'s Eve', '2025-12-31', 'Celebration to welcome the new year.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `Class` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `category`, `Class`, `title`, `content`, `created_at`, `status`, `created_by`, `file_path`, `views`) VALUES
(2, 'General', '', 'Test Notice 3', 'This is for testing.  This is for testing.  This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing. This is for testing.  3', '2025-03-18 10:46:55', 'active', 1, NULL, 0),
(3, 'General', '', 'Test Notice 2', 'This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  This is for testing.  ', '2025-03-18 10:47:53', 'active', 1, NULL, 0),
(4, 'Session 2025-26', '', 'Regarding New Session2025-26', 'Students classs 2-9 Session will start from 2 April 2025', '2025-03-27 15:45:14', 'active', 1, 'uploads/1747402466_resume 2.pdf', 3),
(6, 'General', '', 'holiday', 'tomorrow is holiday on account of raksha bandhan', '2025-06-26 06:25:18', 'active', 1, NULL, 5),
(7, 'General', '', 'holiday', 'tomorrow is holiday on account of raksha bandhan', '2025-06-26 06:25:18', 'active', 1, NULL, 4),
(8, 'IT', 'TY', 'email testing no 1 ', 'email testing no 1 ', '2025-08-06 18:05:32', 'active', NULL, '', 3),
(9, 'IT', 'TY', 'test no 4', 'test no 4 ', '2025-08-06 18:06:20', 'active', NULL, 'Screenshot 2025-08-06 232925.png', 1),
(10, 'IT', 'TY', 'test no 5', 'test no 5', '2025-08-06 18:07:27', 'active', NULL, 'Screenshot 2025-05-14 230439.png', 3),
(11, 'IT', 'TY', 'email test ', 'email test ', '2025-08-06 18:09:37', 'active', 2, '', 2),
(12, 'IT', 'TY', 'EMail Test', 'email test with images ', '2025-08-06 18:10:38', 'active', 2, 'uploads/Screenshot 2025-05-14 230439.png', 2),
(13, 'IT', 'TY', 'Annual Tech Symposium 2025 – Project Submission & Presentation Guidelines', 'All final year students of the BSc Computer Science department are hereby informed that the Annual Tech Symposium 2025 will be held on 5th September 2025. As a part of the symposium, all students are required to submit their final year projects for evaluation and presentation.\r\n\r\nThe deadline for online project submission is 25th August 2025. Each group must upload the project report in PDF format along with a video demonstration (optional but recommended) through the official college portal. Students must ensure that their project report includes the title page, abstract, system requirements, methodologies used, screenshots, and conclusion.\r\n\r\nThe live presentations will be scheduled from 1st to 4th September 2025 in Computer Lab 1 and Lab 2. Each group will get 10 minutes for presentation and 5 minutes for Q&A. The schedule will be shared via the department notice board and class WhatsApp groups.\r\n\r\nAttendance is mandatory for all groups. Internal marks will be based on the submission, presentation clarity, and innovative contribution of the project.\r\n\r\nFor any queries or technical difficulties in uploading the project, students may contact their assigned faculty guides or email techsymposium@college.edu.in.\r\n\r\nWe wish all students the best for their final presentations!\r\n', '2025-08-07 13:58:16', 'active', 1, '', 14),
(14, 'BBA', 'SY', 'test no 26', 'test no 26 ', '2025-08-08 15:33:11', 'active', 2, 'uploads/LENSE1.jpg', 3),
(18, 'IT', 'TY', 'aas', 'All students of **Semester V** are hereby reminded that **today, 26th August 2025, is the final deadline** for submitting your project work. Every student must submit the **hard copy** (spiral bound) along with the **soft copy** (CD/Pen Drive or Email as instructed) to their respective project guides **before 5:00 PM**. Projects submitted after the deadline will not be entertained under any circumstances. Please ensure that your work is complete, properly formatted, and approved by your guide. For doubts or clarifications, kindly reach out to your **class coordinator or project in-charge** immediately.\r\n\r\nBy Order, HOD\r\n', '2025-08-26 10:04:55', 'active', 2, '', 5),
(19, 'IT', 'TY', 'email test ', 'email testing ', '2025-09-05 09:33:07', 'active', 2, 'uploads/DSA_Assignment_JH_30825.pdf', 1),
(20, 'IT', 'TY', 'mail ', 'mail', '2025-09-05 14:05:24', 'active', 2, '', 0),
(21, 'IT', 'TY', 'mail ', 'mail ', '2025-09-05 14:07:12', 'active', 2, 'uploads/DAS_CA_23BSD040.docx', 1),
(22, 'IT', 'TY', 'test no 4', 'test ', '2025-09-12 04:12:17', 'active', 2, 'uploads/DS.pdf', 0),
(23, 'IT', 'TY', 'test no 1 ', 'test no 25', '2025-09-12 04:12:56', 'active', 2, 'uploads/DS.pdf', 0),
(24, 'IT', 'TY', 'test 30', '300', '2025-09-12 07:57:24', 'active', 2, '', 0),
(25, 'IT', 'TY', 'ccxzcz', 'zxzxccc', '2025-09-12 08:05:36', 'active', 2, 'uploads/AWF.pdf', 0),
(26, 'IT', 'TY', 'rrr', 'fffff', '2025-09-12 08:21:13', 'active', 2, '', 0),
(27, 'IT', 'TY', 'dffgdgdg gd f', 'sdg ssgdsfsdfsd gs', '2025-09-12 08:23:01', 'active', 2, '', 0),
(28, 'IT', 'TY', 'ronit ', 'ddsdsv', '2025-09-12 08:44:59', 'active', 2, '', 0),
(29, 'IT', 'TY', 'vxcvcxvvxvvvv', 'dscdzcvzcvzvzvs', '2025-09-12 08:47:25', 'active', 2, '', 0),
(30, 'IT', 'TY', 'vvc v', 'dvdfb', '2025-09-12 08:49:53', 'active', 2, '', 1),
(31, 'IT', 'TY', 'dsvsd fa a', 'ssdfdsf sd fsdfsd fdfsdf sd', '2025-09-12 09:09:06', 'inactive', 2, '', 1),
(32, 'IT', 'TY', 'fdbbcbcv ', 'vxvx', '2025-09-12 09:15:33', 'active', 2, '', 2),
(33, 'IT', 'TY', 'test no 40', 'test no 40 ', '2025-09-25 08:04:28', 'active', 2, 'uploads/AWF.pdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `notice_id` int(11) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `course` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `question`, `created_at`, `notice_id`, `class`, `course`) VALUES
(1, 'what is best game ', '2025-08-24 21:00:55', 14, NULL, NULL),
(2, 'what is best game ', '2025-08-24 22:01:01', 14, NULL, NULL),
(3, 'Which is your favorite subject?', '2025-08-25 18:37:21', NULL, 'TY', 'IT'),
(4, 'What should the college canteen improve the most?', '2025-08-26 15:42:23', NULL, NULL, NULL),
(28, 'test 4 ', '2025-09-07 20:12:29', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`) VALUES
(1, 1, '122'),
(2, 1, '111'),
(3, 2, '122'),
(4, 2, '111'),
(5, 3, 'BD'),
(6, 3, 'AI'),
(7, 3, 'BI'),
(8, 3, 'AWF'),
(9, 4, 'aa'),
(10, 4, 'df'),
(11, 4, 'zv'),
(12, 4, 'vv'),
(59, 28, 'a'),
(60, 28, 'b');

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `voted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_votes`
--

INSERT INTO `poll_votes` (`id`, `poll_id`, `option_id`, `user_id`, `voted_at`) VALUES
(1, 1, 2, '::1', '2025-08-24 21:02:29'),
(2, 3, 7, '23BSD999', '2025-08-25 18:37:38'),
(5, 4, 12, '23BSD040', '2025-08-31 02:23:52'),
(6, 3, 8, '23BSD040', '2025-08-31 02:25:52'),
(7, 2, 4, '23BSD040', '2025-08-31 02:26:26'),
(8, 4, 12, '23BSD999', '2025-09-07 20:07:37'),
(9, 2, 4, '23BSD999', '2025-09-07 20:07:54'),
(10, 1, 2, '23BSD999', '2025-09-07 20:08:02'),
(11, 28, 59, '23BSD999', '2025-09-07 20:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_name`, `email`, `uid`, `class`, `course`, `phone`, `address`, `dob`, `gender`, `password`, `reg_date`) VALUES
(1, 'ronit ', 'ronit.teli159@gmail.com', '23BSD040', 'TY', 'IT', '152346555', '12312312', '2025-08-02', 'Male', '$2y$10$vgMg4ujn..qaEINI3191X.w5ttEzjU1STw1h9ivcwa66eSB0vO9.C', '2025-08-05 16:08:42'),
(2, 'RONIT TELI', 'ronitteli1@gmail.com', '23BSD100', 'TY', 'IT', '08291659185', NULL, '2025-08-07', 'Male', '$2y$10$Za.dNjXFxUmNtq0vY4ar1emO6xwxzBy2CLHiDRuk87BfQ2ONdT4Pu', '2025-08-25 12:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(5) NOT NULL,
  `AdminName` varchar(45) DEFAULT NULL,
  `UserName` char(45) DEFAULT NULL,
  `MobileNumber` bigint(11) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  `role` varchar(255) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`, `role`) VALUES
(1, 'Admin', 'admin', 1234567899, 'admin@gmail.com', 'f77bec1d3a07a56ff42516f7c206568c', '2025-02-15 06:26:14', 'admin'),
(2, 'xyz', 'raiyok', 1234567890, 'raiyok@gmail.com', 'f77bec1d3a07a56ff42516f7c206568c', '2025-05-25 12:45:54', 'admin'),
(3, 'xyz', 'xyz', NULL, 'email1@example.com', 'cc03e747a6afbbcbf8be7668acfebee5', '2025-09-04 20:09:13', 'principal');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `categoryName` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `categoryName`, `creationDate`) VALUES
(1, 'General', '2025-03-18 10:42:52'),
(2, 'Session 2025-26', '2025-03-27 15:44:16'),
(4, 'BBA', '2025-07-11 04:40:45'),
(5, 'IT', '2025-08-05 08:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `tblinquiries`
--

CREATE TABLE `tblinquiries` (
  `id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT 0,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblinquiries`
--

INSERT INTO `tblinquiries` (`id`, `notice_id`, `category`, `user_id`, `full_name`, `email`, `mobile_number`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 7, NULL, 0, 'ronit', 're@sdss.gmail.com', '11111', 'aaaaaa', 'aaaaaaaaaaaaaaaa', 'Pending', '2025-08-04 19:23:08'),
(2, 4, 'Session 2025-26', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'attached file', 'aaaaaaaaaaaaaaa', 'Pending', '2025-08-04 19:25:46'),
(3, 7, 'General', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'aaaaaa', 'asadadasdaasfaf', 'Pending', '2025-08-04 19:36:52'),
(4, 7, 'General', 0, 'RONIT TELI', 're@sdss.gmail.com', '082916544449185', 'aaaaaa', 'asadadasdaasfaf', 'Pending', '2025-08-04 19:38:32'),
(5, 7, 'General', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'fsdfsfsdfvv', 'fdvfddfbdsddfvvfd', 'Pending', '2025-08-05 15:37:51'),
(6, 7, 'General', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'fsdfsfsdfvv', 'fdvfddfbdsddfvvfd', 'Pending', '2025-08-05 15:38:14'),
(7, 6, 'General', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'gdgdgdgdfgdf', 'bgfgfbfgbgfbfbfgbfgbg', 'Answered', '2025-08-05 15:38:42'),
(8, 13, 'IT', 0, 'RONIT TELI', 'ronit.teli159@gmail.com', '', 'test 25', 'test no 25', 'Answered', '2025-09-12 09:26:17'),
(9, 18, 'IT', 0, '', 'ronit@gamil.com', '', '', 'dvzxvvxvcxvcxvz  dcz ', 'Pending', '2025-09-12 13:17:52'),
(10, 18, 'IT', 0, '', 'ronit@gamil.com', '', '', 'dvzxvvxvcxvcxvz  dcz ', 'Pending', '2025-09-12 13:30:54'),
(11, 18, 'IT', 0, '', 'ronit@gamil.com', '', '', 'dvzxvvxvcxvcxvz  dcz ', 'Pending', '2025-09-12 13:32:17'),
(12, 18, 'IT', 0, '', 'ronit@gamil.com', '', '', 'dvzxvvxvcxvcxvz  dcz ', 'Pending', '2025-09-12 13:33:07'),
(13, 18, 'IT', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'test 1 ', 'sdczcc', 'Pending', '2025-09-12 13:33:18'),
(14, 33, 'IT', 0, 'RONIT TELI', 're@sdss.gmail.com', '08291659185', 'test 1 ', 'test no 111', 'Pending', '2025-09-25 14:08:05'),
(15, 13, 'IT', 0, 'ronit teli ', 'ronitteli1@gmail.com', '1234567890', 'test 50', 'test 50 fdvsdfsdvdsd sdf s', 'Pending', '2025-09-26 14:50:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notice_id` (`notice_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `cat` (`category`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notice_id` (`notice_id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_options_ibfk_1` (`poll_id`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departmentName` (`categoryName`);

--
-- Indexes for table `tblinquiries`
--
ALTER TABLE `tblinquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inquiry_notice` (`notice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblinquiries`
--
ALTER TABLE `tblinquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`id`);

--
-- Constraints for table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `polls_ibfk_1` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`id`);

--
-- Constraints for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD CONSTRAINT `poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD CONSTRAINT `poll_votes_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`),
  ADD CONSTRAINT `poll_votes_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `poll_options` (`id`);

--
-- Constraints for table `tblinquiries`
--
ALTER TABLE `tblinquiries`
  ADD CONSTRAINT `fk_inquiry_notice` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
