-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 31, 2025 at 10:56 AM
-- Server version: 8.0.40
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `senior project`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sender_role` enum('student','teacher') NOT NULL,
  `sender_id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `email`, `subject`, `message`, `created_at`, `sender_role`, `sender_id`, `first_name`, `last_name`) VALUES
(1, 'sajeda@gmail.com', 'hello', 'how are you', '2025-05-20 09:56:23', 'student', 0, '', ''),
(2, 'ccmc895@gmail.com', 'hi', '.', '2025-05-25 12:30:42', 'teacher', 49, '', ''),
(3, 'ccmc895@gmail.com', 'hi', '..', '2025-05-25 13:21:41', 'teacher', 101, '', ''),
(4, 'hala1@gmail.com', 'hello', 'hi', '2025-05-27 15:41:51', 'student', 130, '', ''),
(5, 'hala1@gmail.com', 'hello', '..', '2025-05-27 15:43:37', 'student', 130, 'hala', 'saleh'),
(6, 'zainab8@gmail.com', 'major changed', 'i want to change my major', '2025-05-27 19:57:38', 'teacher', 137, 'zainab', 'jomaa'),
(7, 'ayatkh18@gmail.com', 'hello', 'how are you', '2025-05-28 06:50:54', 'teacher', 136, 'ayat', 'khalaf'),
(8, 'qamar@gmail.com', 'hi', 'hello', '2025-05-28 20:34:50', 'teacher', 144, 'qamar', 'hassan'),
(9, 'ghadeerkhalaf@gmail.com', 'hi', 'hello', '2025-05-29 06:32:32', 'student', 165, 'ghadeer', 'khalaf'),
(10, 'ayatkhalaf@gmail.com', 'hello', 'how are you', '2025-05-29 06:33:59', 'teacher', 166, 'ayat', 'khalaf'),
(11, 'malakhassan@gmail.com', 'hi', 'hello', '2025-05-29 10:04:53', 'student', 167, 'malak', 'hassan'),
(12, 'ayshashyah20@gmail.com', 'hi', 'hello', '2025-05-29 10:11:02', 'teacher', 169, 'ayasha', 'shayah');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `major_id` int DEFAULT NULL,
  `schedule_duration` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `major_id` (`major_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `title`, `description`, `price`, `image`, `major_id`, `schedule_duration`, `course_name`, `room_number`) VALUES
(20, 'Web Development', 'Web development is the process of creating, building, and maintaining websites and web applications. It encompasses various areas', 56.00, 'uploads/courses/681ccdca62276_web2.jpg', 15, '8 Weeks', 'CSCI390', '115'),
(24, 'Java1', 'Java is the #1 programming language and development platform for enterprises and developers. Learn how to download, use, and develop Java applications for desktops and other devices.', 56.00, 'uploads/courses/68224afde79a4_java.jpg', 15, '8 Weeks', 'CSCI390', '202'),
(26, 'Concept of programmig', 'How does a programming language emerge and become successful? What key characteristics make an ideal programming language?', 55.00, 'uploads/courses/68348b9dc3194_concept.jpg', 15, '4 Weeks', 'CSCI351', '200'),
(27, 'Introduction to programming', 'This course introduces the basic concepts and principles of structured programming in Java. It starts with an introduction to Java showing its syntax and the structure of a program in Java then teaches simple data types, control structures, methods, arrays, and strings.', 32.00, 'uploads/courses/68348fdc56c12_Introduction of programming.jpg', 15, '4 Weeks', 'CSCI250', '203'),
(28, 'Database System', 'This course introduces fundamentals of database systems. It starts by motivating the need of the database approach in real life scenarios and the benefit of adopting a Database Management System (DBMS). This course includes data modeling (based on the entity relationship model), data normalization and data manipulation using SQL queries. Students will learn how to design, implement and query a relational database by using a Microsoft SQL Server DBMS.', 44.00, 'uploads/courses/683490c7af41a_database.jpg', 15, '4 Weeks', 'CSCI 335', '104'),
(29, 'Data Structure', 'This course covers the design and implementation of important data structures and their algorithms in addition to their time complexity using the big-Oh notation. The data structures considered include stacks, queues, lists, linked lists, trees, and graphs. An approach based on abstract data types and classes will be emphasized and sorting algorithms are discussed.', 47.00, 'uploads/courses/683491100b693_data structure.jpg', 15, '6 Weeks', 'CSCI378', '220'),
(30, 'Introduction to Biology', 'An overview of life’s basic principles including cells, genetics, and evolution.', 24.00, 'uploads/courses/6835f4505eb4d_Bio101.jpg', 13, '4 Weeks', 'BIO101', '117'),
(31, 'Genetics', 'A study of genes, DNA, heredity, and genetic variation in organisms.', 55.00, 'uploads/courses/6835f5fb33e2f_genetics.jpg', 13, '6 Weeks', 'BIO201', '204'),
(32, 'Ecology and Environmental Biology', 'Examines interactions between organisms and their environments and ecosystems.', 41.00, 'uploads/courses/6835f639b77c1_Ecology.jpg', 13, '6 Weeks', 'BIO220', '311'),
(33, 'Human Anatomy and Physiology', 'Covers the structure and function of human body systems in health and disease.', 33.00, 'uploads/courses/6835f676b8382_Human Anatomy and Physiology.jpg', 13, '8 Weeks', 'BIO301', '113'),
(35, 'Cell Biology', 'Introduction to the structure and function of cells, including organelles, membranes, and cellular processes.', 22.00, 'uploads/courses/68361ebe7f36b_cell biology.jpg', 21, '6 Weeks', 'BIO101', '013'),
(36, 'Molecular Genetics', 'Study of the molecular basis of genetic inheritance, including DNA replication, transcription, and gene regulation.', 31.00, 'uploads/courses/68361f04f1128_Molecular Genetics.jpg', 21, '6 Weeks', 'BIO201', '012'),
(37, 'Human Physiology', 'Exploration of the systems that maintain homeostasis in the human body, including nervous, circulatory, and respiratory systems.', 36.00, 'uploads/courses/68361f473ea31_Human Physiology.jpg', 21, '8 Weeks', 'BIO202', '120'),
(38, 'Biochemistry', 'Analysis of the chemical processes and substances that occur within living organisms.', 54.00, 'uploads/courses/68361f80d7973_Biochemistry.jpg', 21, '6 Weeks', 'BIO301', '214'),
(39, 'Immunology', 'Examination of the immune system’s components, functions, and its role in health and disease.', 48.00, 'uploads/courses/68361fc065d0d_Immunology.jpg', 21, '12 Weeks', 'BIO302', '110'),
(40, 'General Chemistry I', 'Introduction to the fundamental principles of chemistry including atomic structure, bonding, stoichiometry, and chemical reactions.', 33.00, 'uploads/courses/6836217280859_Organic Chemistry I.jpg', 18, '4 Weeks', 'CHE101', '218'),
(41, 'General Chemistry II', 'Continuation of General Chemistry I with topics such as chemical kinetics, equilibrium, thermodynamics, and electrochemistry.', 53.00, 'uploads/courses/68362214ab62c_Organic Chemistry II.webp', 18, '6 Weeks', 'CHE102', '213'),
(42, 'Organic Chemistry I', 'Study of the structure, nomenclature, reactions, and synthesis of organic compounds with emphasis on hydrocarbons and functional groups.', 61.00, 'uploads/courses/68362266aa773_Organic Chemistry I.jpg', 18, '8 Weeks', 'CHE201', '108'),
(43, 'Organic Chemistry II', 'Continuation of Organic Chemistry I, focusing on reaction mechanisms, aromatic compounds, and advanced synthesis techniques.', 44.00, 'uploads/courses/683622d1be3e5_Organic Chemistry II.webp', 18, '8 Weeks', 'CHE202', '205'),
(44, 'Operating System', 'Operating System', 55.00, 'uploads/courses/68362d39e8fdb_operating system.jpg', 15, '8 Weeks', 'CSCI430', '210'),
(51, 'Arifitial Intellegance', 'AI', 44.00, 'uploads/courses/6836cf5adfcaa_AI 2.jpg', 15, '6 Weeks', 'CSCI 480', '105'),
(53, 'Network security', 'Network security is the practice of protecting computer networks from unauthorized access, misuse, and cyber threats, ensuring the confidentiality, integrity, and availability of data.', 44.00, 'uploads/6837fc7c1c4a0_network security 2.jpg', 15, '6 Weeks', 'CSCI362', '102'),
(55, 'Microbiology', 'Explores microorganisms and their impact on health, industry, and the environment.', 44.00, 'uploads/courses/6837007aa8450_Microbiology.jpg', 13, '4 Weeks', 'Bio320', '332'),
(56, 'Microbiology', 'Explores microorganisms and their impact on health, industry, and the environment.', 44.00, 'uploads/courses/6837007aa8450_Microbiology.jpg', 13, '4 Weeks', 'Bio320', '332'),
(59, 'Network security', 'network', 33.00, 'uploads/courses/68383425de710_network security 2.jpg', 24, '6 Weeks', 'CSCI362', '001'),
(60, 'Network security', 'network', 33.00, 'uploads/courses/68383425de710_network security 2.jpg', 24, '6 Weeks', 'CSCI362', '001'),
(61, 'Network security', 'network', 33.00, 'uploads/courses/68383425de710_network security 2.jpg', 24, '6 Weeks', 'CSCI362', '001');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

DROP TABLE IF EXISTS `course_materials`;
CREATE TABLE IF NOT EXISTS `course_materials` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`id`, `course_id`, `teacher_id`, `title`, `description`, `file_path`, `uploaded_at`) VALUES
(1, 19, 31, 'Week 1 – HTML Basics', 'Lecture slides and assignment for HTML elements', 'uploads/materials/681f2d2b8ab06_The rise of anonymous web browsing (1).docx', '2025-05-10 10:40:43'),
(2, 19, 55, 'Lecture 2', 'Baics of html', 'uploads/materials/682ed49de94b9_Ghadeer khalaf_final documentation.docx', '2025-05-22 07:39:09'),
(3, 19, 98, 'Lecture 1', 'basics of html', 'uploads/materials/682eec98adf18_The_Rise_of_Anonymous_Web_Browsing_Presentation.pptx', '2025-05-22 09:21:28'),
(4, 19, 55, 'Lecture 1', 'basics of html', 'uploads/materials/682ef95303582_Ghadeer_khalaf_final_documentation.docx', '2025-05-22 10:15:47'),
(5, 19, 122, 'Operating System', 'introduction', 'uploads/materials/68346bc0c0356_Concept-project_2__1_.docx', '2025-05-26 13:25:20'),
(6, 24, 55, 'Exam 1', 'note that the exam 1 will be held tommorow', NULL, '2025-05-27 07:22:07'),
(7, 51, 141, 'Lecture 1', 'introduction of AI', 'uploads/materials/68377672585fb_1748446611_1748415145_1748202170_Skills_exam_configuration.docx', '2025-05-28 20:47:46'),
(8, 57, 166, 'Introduction', 'Introduction', 'uploads/materials/6837fe93e53fe_1748415145_1748202170_Skills_exam_configuration__1___1_.docx', '2025-05-29 06:28:35'),
(9, 58, 166, 'Introduction', 'introduction', 'uploads/materials/6837fedc03805_1748415145_1748202170_Skills_exam_configuration__1___1_.docx', '2025-05-29 06:29:48'),
(10, 57, 166, 'Basics', '', NULL, '2025-05-29 09:05:13'),
(11, 58, 166, 'Schedule exam', '', 'uploads/materials/68382382043f0_Blank_board.png', '2025-05-29 09:06:10'),
(12, 20, 169, 'Introduction', 'basics of html', 'uploads/materials/6838328eee5b1_6837fedc03805_1748415145_1748202170_Skills_exam_configuration__1___1_.docx', '2025-05-29 10:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `course_schedule`
--

DROP TABLE IF EXISTS `course_schedule`;
CREATE TABLE IF NOT EXISTS `course_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course_schedule`
--

INSERT INTO `course_schedule` (`id`, `course_id`, `day_of_week`, `start_time`, `end_time`, `room_number`) VALUES
(83, 51, 'Monday', '08:00:00', '09:00:00', NULL),
(84, 51, 'Tuesday', '08:00:00', '09:00:00', NULL),
(85, 44, 'Monday', '10:00:00', '11:30:00', NULL),
(86, 44, 'Tuesday', '10:00:00', '11:30:00', NULL),
(87, 44, 'Wednesday', '10:00:00', '11:30:00', NULL),
(88, 44, 'Thursday', '10:00:00', '11:30:00', NULL),
(89, 29, 'Monday', '11:00:00', '12:00:00', NULL),
(90, 29, 'Tuesday', '11:00:00', '12:00:00', NULL),
(91, 28, 'Monday', '09:00:00', '10:00:00', NULL),
(92, 28, 'Tuesday', '09:00:00', '10:00:00', NULL),
(94, 43, 'Monday', '14:00:00', '15:00:00', NULL),
(95, 42, 'Monday', '11:00:00', '12:00:00', NULL),
(96, 42, 'Tuesday', '11:00:00', '12:00:00', NULL),
(103, 27, 'Monday', '13:00:00', '15:00:00', NULL),
(104, 27, 'Tuesday', '13:00:00', '15:00:00', NULL),
(105, 26, 'Monday', '10:30:00', '11:30:00', NULL),
(106, 26, 'Tuesday', '10:30:00', '11:30:00', NULL),
(107, 24, 'Wednesday', '14:00:00', '16:00:00', NULL),
(108, 20, 'Monday', '14:30:00', '16:00:00', NULL),
(109, 20, 'Tuesday', '14:30:00', '16:00:00', NULL),
(117, 32, 'Monday', '10:00:00', '11:00:00', NULL),
(118, 31, 'Wednesday', '13:00:00', '15:00:00', NULL),
(119, 31, 'Thursday', '13:00:00', '15:00:00', NULL),
(120, 30, 'Monday', '08:00:00', '09:00:00', NULL),
(121, 30, 'Tuesday', '08:00:00', '09:00:00', NULL),
(126, 40, 'Wednesday', '15:00:00', '16:00:00', NULL),
(127, 40, 'Thursday', '15:00:00', '16:00:00', NULL),
(132, 38, 'Monday', '08:00:00', '09:00:00', NULL),
(133, 38, 'Tuesday', '08:00:00', '09:00:00', NULL),
(134, 37, 'Wednesday', '08:00:00', '09:00:00', NULL),
(135, 37, 'Thursday', '08:00:00', '09:00:00', NULL),
(136, 36, 'Monday', '14:00:00', '15:00:00', NULL),
(137, 36, 'Tuesday', '14:00:00', '15:00:00', NULL),
(138, 36, 'Wednesday', '14:00:00', '15:00:00', NULL),
(139, 36, 'Thursday', '14:00:00', '15:00:00', NULL),
(140, 35, 'Wednesday', '13:00:00', '14:00:00', NULL),
(141, 35, 'Thursday', '13:00:00', '14:00:00', NULL),
(142, 55, 'Monday', '08:00:00', '09:00:00', NULL),
(143, 55, 'Tuesday', '08:00:00', '09:00:00', NULL),
(144, 56, 'Wednesday', '09:00:00', '10:00:00', NULL),
(145, 56, 'Thursday', '09:00:00', '10:00:00', NULL),
(146, 41, 'Wednesday', '10:00:00', '12:00:00', NULL),
(147, 41, 'Thursday', '10:00:00', '12:00:00', NULL),
(148, 39, 'Wednesday', '10:00:00', '11:00:00', NULL),
(149, 39, 'Thursday', '10:00:00', '11:00:00', NULL),
(150, 33, 'Monday', '08:00:00', '09:00:00', NULL),
(151, 33, 'Tuesday', '08:00:00', '09:00:00', NULL),
(158, 53, 'Wednesday', '08:00:00', '09:00:00', NULL),
(159, 53, 'Thursday', '08:00:00', '09:00:00', NULL),
(160, 59, 'Monday', '08:00:00', '09:00:00', NULL),
(161, 60, 'Tuesday', '08:00:00', '08:00:00', NULL),
(162, 61, 'Wednesday', '09:00:00', '10:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `teacher_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`, `teacher_id`) VALUES
(1, 21, 13, '2025-05-05 17:40:50', NULL),
(2, 3, 13, '2025-05-06 12:56:33', NULL),
(3, 3, 14, '2025-05-06 12:56:33', NULL),
(4, 3, 13, '2025-05-06 12:56:57', NULL),
(5, 3, 14, '2025-05-06 12:56:57', NULL),
(6, 3, 15, '2025-05-06 12:56:57', NULL),
(7, 22, 14, '2025-05-06 13:07:44', NULL),
(8, 22, 14, '2025-05-06 13:07:54', NULL),
(9, 22, 13, '2025-05-06 13:18:31', NULL),
(10, 32, 19, '2025-05-10 10:43:21', NULL),
(11, 7, 22, '2025-05-10 17:58:30', NULL),
(15, 39, 23, '2025-05-11 15:45:14', NULL),
(16, 32, 23, '2025-05-11 15:53:36', NULL),
(17, 40, 23, '2025-05-11 16:04:54', NULL),
(18, 41, 23, '2025-05-11 16:11:14', NULL),
(19, 42, 20, '2025-05-11 16:12:46', NULL),
(20, 53, 20, '2025-05-13 08:59:42', NULL),
(21, 50, 20, '2025-05-16 18:23:22', NULL),
(22, 50, 22, '2025-05-16 18:54:41', NULL),
(23, 50, 19, '2025-05-16 19:28:15', 45),
(24, 56, 19, '2025-05-19 17:41:38', 55),
(25, 56, 20, '2025-05-19 17:41:38', 55),
(26, 56, 24, '2025-05-19 17:41:38', 55),
(27, 72, 20, '2025-05-21 18:59:21', 55),
(28, 72, 19, '2025-05-21 19:00:15', 31),
(29, 72, 24, '2025-05-21 19:00:15', 55),
(30, 53, 19, '2025-05-22 07:16:50', 45),
(32, 95, 20, '2025-05-22 09:13:52', 55),
(33, 96, 19, '2025-05-22 09:20:28', 98),
(36, 99, 24, '2025-05-22 10:14:19', 55),
(37, 99, 25, '2025-05-23 18:35:47', 101),
(38, 97, 25, '2025-05-23 18:39:33', 101),
(39, 54, 20, '2025-05-23 20:00:10', 55),
(40, 71, 20, '2025-05-24 17:29:23', 74),
(41, 115, 20, '2025-05-25 15:24:44', 74),
(42, 114, 20, '2025-05-25 16:47:21', 55),
(44, 114, 19, '2025-05-25 17:07:39', 98),
(45, 109, 19, '2025-05-25 20:16:41', 55),
(48, 121, 19, '2025-05-26 13:27:51', 122),
(49, 109, 20, '2025-05-26 17:55:53', 122),
(50, 109, 24, '2025-05-26 17:55:53', 55),
(51, 109, 26, '2025-05-26 17:55:53', 122),
(52, 130, 19, '2025-05-27 15:08:53', 98),
(55, 99, 29, '2025-05-27 21:50:59', 55),
(57, 99, 28, '2025-05-27 22:31:43', 136),
(58, 154, 52, '2025-05-28 09:07:57', 140),
(59, 154, 20, '2025-05-28 14:40:40', 117),
(61, 130, 51, '2025-05-28 20:44:30', 141),
(62, 130, 24, '2025-05-28 20:53:20', 141),
(63, 154, 26, '2025-05-28 21:56:56', 140),
(66, 165, 53, '2025-05-29 06:08:03', 141),
(67, 165, 58, '2025-05-29 06:26:24', 166),
(68, 154, 28, '2025-05-29 06:52:49', 141),
(69, 155, 35, '2025-05-29 07:40:10', 152),
(70, 155, 36, '2025-05-29 07:40:10', 152),
(71, 155, 37, '2025-05-29 07:40:10', 152),
(72, 155, 39, '2025-05-29 07:40:10', 152),
(73, 156, 24, '2025-05-29 07:41:16', 143),
(74, 156, 51, '2025-05-29 07:41:16', 146),
(75, 157, 28, '2025-05-29 07:42:40', 141),
(76, 157, 51, '2025-05-29 07:42:40', 140),
(78, 157, 20, '2025-05-29 07:43:18', 117),
(79, 160, 28, '2025-05-29 07:44:17', 149),
(80, 160, 51, '2025-05-29 07:44:17', 143),
(81, 164, 31, '2025-05-29 07:45:34', 137),
(82, 164, 55, '2025-05-29 07:45:34', 137),
(83, 161, 57, '2025-05-29 07:46:51', 166),
(84, 159, 58, '2025-05-29 07:48:25', 166),
(85, 167, 26, '2025-05-29 10:04:05', 140),
(86, 167, 28, '2025-05-29 10:04:05', 143),
(87, 167, 53, '2025-05-29 10:04:05', 143),
(88, 165, 24, '2025-05-30 09:53:51', 141),
(89, 165, 26, '2025-05-30 09:53:51', 140);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `student_id` int NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `comment` text,
  `graded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_grade` (`course_id`,`teacher_id`,`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `course_id`, `teacher_id`, `student_id`, `grade`, `comment`, `graded_at`) VALUES
(1, 19, 31, 32, '87', '', '2025-05-10 16:11:53'),
(2, 20, 49, 53, '66', '', '2025-05-13 10:21:55'),
(3, 19, 55, 56, '98', '', '2025-05-27 03:55:21'),
(4, 19, 55, 71, '67', '', '2025-05-23 17:54:25'),
(5, 19, 55, 99, '44', '', '2025-05-27 03:55:21'),
(6, 19, 55, 109, '77', 'good', '2025-05-27 03:55:21'),
(16, 24, 55, 56, '98', '', '2025-05-27 03:38:27'),
(17, 24, 55, 72, '68', '', '2025-05-27 03:38:27'),
(18, 24, 55, 99, '66', '', '2025-05-27 03:38:27'),
(19, 24, 55, 109, '99', '', '2025-05-27 03:38:27'),
(23, 29, 55, 130, '99', '', '2025-05-28 03:58:07'),
(24, 29, 55, 99, '92', '', '2025-05-28 03:58:07'),
(27, 51, 141, 130, '88', '', '2025-05-28 17:51:14'),
(28, 58, 166, 165, '49', '', '2025-05-29 04:48:52'),
(31, 58, 166, 159, '88', '', '2025-05-29 04:48:52'),
(32, 31, 137, 164, '88', '', '2025-05-29 07:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

DROP TABLE IF EXISTS `major`;
CREATE TABLE IF NOT EXISTS `major` (
  `id` int NOT NULL AUTO_INCREMENT,
  `major_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `major_name`, `description`, `image`) VALUES
(13, 'Biology', 'Biology is the science of life that encompasses the study of living organisms, their structure, function, growth, evolution, and interactions with their environment.', 'uploads/majors/683483873c4ef_Biology.jpg'),
(15, 'Computer Science', 'Learn the basics of HTML, CSS, and JavaScript to start in your journey in web development.', 'uploads/majors/67ee35c81f583_CS.jpg'),
(18, 'Chemistry', 'Chemistry is the study of matter and the changes it undergoes and considers both macroscopic and microscopic information. Matter is anything that has mass and occupies space.', 'uploads/majors/683482f4ac403_chemistry.jpg'),
(20, 'Physics', 'Physics is the scientific study of matter, its fundamental constituents, its motion and behavior through space and time, and the related entities of energy and force.', 'uploads/majors/683483d7504aa_Physics.jpg'),
(21, 'Biomedical Science', 'Biomedical science is a multidisciplinary field that applies natural and formal sciences to improve healthcare and public health through research, diagnostics, and technology development.', 'uploads/majors/683484836e371_biomedical.jpg'),
(22, 'pharmacy', 'Pharmacy is the science and practice of discovering, producing, preparing, dispensing, reviewing and monitoring medications, aiming to ensure the safe, effective, and affordable use of medicines.', 'uploads/majors/683485b1ce73c_Pharmacy.jpg'),
(24, 'IT', 'Information technology', 'uploads/majors/683833bcf3d23_AI 2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Ghadeer yasser khalaf', 'ccmc895@gmail.com', 'hi', 'll', '2025-05-26 15:42:11');

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

DROP TABLE IF EXISTS `message_replies`;
CREATE TABLE IF NOT EXISTS `message_replies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `reply_text` text NOT NULL,
  `admin_id` int NOT NULL,
  `sender_role` enum('student','teacher') NOT NULL,
  `sender_id` int NOT NULL,
  `replied_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `message_replies`
--

INSERT INTO `message_replies` (`id`, `message_id`, `reply_text`, `admin_id`, `sender_role`, `sender_id`, `replied_at`) VALUES
(1, 3, 'hello', 7, 'teacher', 101, '2025-05-25 16:22:58'),
(2, 5, 'you can get better in skills exam ', 7, 'student', 130, '2025-05-27 18:46:53'),
(3, 2, 'hello', 7, 'teacher', 49, '2025-05-27 20:31:59'),
(4, 10, 'how are you', 7, 'teacher', 166, '2025-05-29 09:39:49'),
(5, 12, 'how are you', 7, 'teacher', 169, '2025-05-29 13:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `course_id`, `amount`, `payment_date`) VALUES
(1, 99, 19, 55.00, '2025-05-24 19:26:29'),
(2, 99, 19, 55.00, '2025-05-24 19:31:17'),
(3, 99, 20, 56.00, '2025-05-24 19:31:17'),
(4, 99, 24, 56.00, '2025-05-24 19:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

DROP TABLE IF EXISTS `student_applications`;
CREATE TABLE IF NOT EXISTS `student_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `major` int DEFAULT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `major` (`major`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_applications`
--

INSERT INTO `student_applications` (`id`, `user_id`, `username`, `email`, `major`, `approved`, `first_name`, `last_name`, `phone`) VALUES
(23, 130, 'hala', 'hala1@gmail.com', NULL, 1, NULL, NULL, '+9617123463'),
(24, 154, 'ayasha', 'aysha@gmail.com', NULL, 0, NULL, NULL, '+96171234636'),
(25, 155, 'ghada', 'ghada@gmail.com', 21, 1, NULL, NULL, '+96171234636'),
(26, 156, 'zainab', 'zainab@gmail.com', NULL, 1, NULL, NULL, '+96171234553'),
(27, 157, 'hiba', 'hiba@gmail.com', 15, 1, NULL, NULL, '+96171234542'),
(28, 158, 'ahmad', 'ahmad@gmail.com', NULL, 1, NULL, NULL, '+96171234636'),
(29, 159, 'mhmd', 'mohammad@gmail.com', 15, 1, NULL, NULL, '+96171234553'),
(30, 160, 'eman', 'eman@gmail.com', 15, 1, NULL, NULL, '+96171234553'),
(31, 161, 'ziad', 'ziad4@gmail.com', 15, 1, NULL, NULL, '+96171234452'),
(32, 162, 'ziad', 'ziad@gmail.com', NULL, 1, NULL, NULL, '+96171234452'),
(33, 163, 'aya', 'ccmc895@gmail.com', NULL, 1, NULL, NULL, '+96171234636'),
(34, 164, 'marwa', 'marwa@gmail.com', 13, 1, NULL, NULL, '+96171234636'),
(35, 165, 'ghadeer', 'ghadeerkhalaf@gmail.com', 15, 1, NULL, NULL, '+96171234452'),
(36, 167, 'malak', 'malakhassan@gmail.com', 15, 1, NULL, NULL, '+96171234635');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

DROP TABLE IF EXISTS `student_courses`;
CREATE TABLE IF NOT EXISTS `student_courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enrollment` (`student_id`,`course_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `major_id` int NOT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(20) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `email`, `major_id`, `cv_file`, `is_approved`, `created_at`, `phone`, `first_name`, `last_name`) VALUES
(7, 110, 'sajedaa2@gmail.com', 15, 'uploads/Concept-project 2.docx', 1, '2025-05-24 10:40:36', NULL, '', ''),
(15, 137, 'zainab8@gmail.com', 13, '0', 1, '2025-05-27 19:43:16', '+96171234635', 'zainab', 'jomaa'),
(18, 140, 'hala@gmail.com', 15, '0', 1, '2025-05-28 09:02:56', '+96171234452', 'hala', 'saleh'),
(19, 150, 'sajeda3@gmail.com', 15, '0', 1, '2025-05-28 15:16:17', '+96171894635', 'sajeda', 'krayem'),
(20, 152, 'abdo@gmail.com', 21, '0', 1, '2025-05-28 15:23:44', '+96171234636', 'abdo', 'hassan'),
(21, 149, 'sajeda2@gmail.com', 15, '0', 1, '2025-05-28 15:23:47', '+96171894635', 'sajeda', 'jomaa'),
(22, 146, 'ziad1@gmail.com', 15, '0', 1, '2025-05-28 15:23:48', '+96171234452', 'ziad', 'hassan'),
(23, 144, 'qamar@gmail.com', 15, '0', 1, '2025-05-28 15:23:50', '+96171894635', 'qamar', 'hassan'),
(24, 143, 'samer@gmail.com', 15, '0', 1, '2025-05-28 15:23:51', '+96171894635', 'samer', 'khalaf'),
(25, 141, 'mona@gmail.com', 15, '0', 1, '2025-05-28 15:23:52', '+96171234635', 'mona', 'khalaf'),
(26, 166, 'ayatkhalaf@gmail.com', 15, '0', 1, '2025-05-29 06:25:32', '+96171234635', 'ayat', 'khalaf'),
(27, 169, 'ayshashyah20@gmail.com', 15, '0', 1, '2025-05-29 10:08:46', '+96171234635', 'ayasha', 'shayah');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_applications`
--

DROP TABLE IF EXISTS `teacher_applications`;
CREATE TABLE IF NOT EXISTS `teacher_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `major` varchar(100) NOT NULL,
  `cv_file` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `cv_updated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teacher_applications`
--

INSERT INTO `teacher_applications` (`id`, `user_id`, `email`, `major`, `cv_file`, `status`, `submitted_at`, `first_name`, `last_name`, `phone`, `cv_updated`) VALUES
(29, 137, 'zainab8@gmail.com', 'Biology', 'uploads/1748447057_1748415145_1748202170_Skills exam configuration.docx', 'approved', '2025-05-27 19:42:57', 'zainab', 'jomaa', '+961712346399', 1),
(30, 140, 'hala@gmail.com', 'Computer Science', 'uploads/68346bc0c0356_Concept-project_2__1_ (3).docx', 'approved', '2025-05-28 07:28:33', 'hala', 'saleh', '+96171234452', 0),
(31, 141, 'mona@gmail.com', 'Computer Science', 'uploads/681f2d2b8ab06_The rise of anonymous web browsing (1) (1).docx', 'approved', '2025-05-28 07:30:36', 'mona', 'khalaf', '+96171234635', 0),
(32, 143, 'samer@gmail.com', 'Computer Science', 'uploads/68346bc0c0356_Concept-project_2__1_ (2).docx', 'approved', '2025-05-28 07:32:18', 'samer', 'khalaf', '+96171894635', 0),
(33, 144, 'qamar@gmail.com', 'Computer Science', 'uploads/1748415145_1748202170_Skills exam configuration.docx', 'approved', '2025-05-28 07:33:45', 'qamar', 'hassan', '+96171894635', 0),
(34, 146, 'ziad1@gmail.com', 'Computer Science', 'uploads/1748415145_1748202170_Skills exam configuration.docx', 'approved', '2025-05-28 07:35:09', 'ziad', 'hassan', '+96171234452', 0),
(35, 149, 'sajeda2@gmail.com', 'Computer Science', 'uploads/68346bc0c0356_Concept-project_2__1_ (3).docx', 'approved', '2025-05-28 07:37:58', 'sajeda', 'jomaa', '+96171894635', 0),
(36, 150, 'sajeda3@gmail.com', 'Computer Science', 'uploads/1748415145_1748202170_Skills exam configuration.docx', 'approved', '2025-05-28 07:39:04', 'sajeda', 'krayem', '+96171894635', 0),
(37, 152, 'abdo@gmail.com', 'Biomedical Science', 'uploads/1748415145_1748202170_Skills exam configuration.docx', 'approved', '2025-05-28 07:41:53', 'abdo', 'hassan', '+96171234636', 0),
(38, 153, 'ghadeer@gmail.com', 'Biology', 'uploads/1748415145_1748202170_Skills exam configuration.docx', 'rejected', '2025-05-28 07:43:26', 'Ghadeer', 'khalaf', '+96171234636', 0),
(39, 166, 'ayatkhalaf@gmail.com', 'Computer Science', 'uploads/1748500858_68346bc0c0356_Concept-project_2__1_ (3).docx', 'approved', '2025-05-29 06:25:04', 'ayat', 'khalaf', '+96171234635', 1),
(40, 169, 'ayshashyah20@gmail.com', 'Computer Science', 'uploads/1748415145_1748202170_Skills exam configuration (1) (1).docx', 'approved', '2025-05-29 10:08:11', 'ayasha', 'shayah', '+96171234635', 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_courses`
--

DROP TABLE IF EXISTS `teacher_courses`;
CREATE TABLE IF NOT EXISTS `teacher_courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enrollment` (`teacher_id`,`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teacher_courses`
--

INSERT INTO `teacher_courses` (`id`, `teacher_id`, `course_id`, `enrolled_at`) VALUES
(1, 31, 19, '2025-05-10 07:49:04'),
(2, 31, 20, '2025-05-10 07:49:25'),
(3, 31, 22, '2025-05-10 07:51:43'),
(4, 47, 20, '2025-05-12 20:52:09'),
(5, 47, 19, '2025-05-12 20:57:04'),
(6, 48, 22, '2025-05-12 21:02:30'),
(7, 49, 20, '2025-05-13 10:14:26'),
(8, 45, 20, '2025-05-16 19:12:46'),
(9, 45, 19, '2025-05-16 19:13:20'),
(10, 45, 24, '2025-05-16 19:35:32'),
(14, 74, 20, '2025-05-22 07:13:12'),
(16, 98, 19, '2025-05-22 09:19:40'),
(17, 101, 25, '2025-05-23 18:34:30'),
(18, 117, 20, '2025-05-25 19:43:57'),
(19, 122, 20, '2025-05-26 13:23:24'),
(20, 122, 19, '2025-05-26 13:23:37'),
(21, 122, 26, '2025-05-26 17:01:08'),
(22, 55, 29, '2025-05-27 15:07:11'),
(26, 132, 20, '2025-05-27 18:46:08'),
(44, 136, 48, '2025-05-28 05:24:58'),
(48, 136, 50, '2025-05-28 05:48:55'),
(49, 136, 49, '2025-05-28 05:49:05'),
(50, 140, 52, '2025-05-28 09:03:46'),
(51, 140, 51, '2025-05-28 09:03:54'),
(60, 137, 31, '2025-05-28 14:09:54'),
(61, 137, 55, '2025-05-28 14:21:51'),
(62, 140, 26, '2025-05-28 14:35:04'),
(63, 141, 51, '2025-05-28 20:31:52'),
(64, 141, 53, '2025-05-28 20:31:56'),
(65, 141, 44, '2025-05-28 20:32:01'),
(66, 141, 24, '2025-05-28 20:32:08'),
(67, 141, 20, '2025-05-28 20:32:11'),
(68, 141, 28, '2025-05-28 20:32:19'),
(69, 143, 51, '2025-05-28 20:32:56'),
(70, 143, 26, '2025-05-28 20:33:01'),
(71, 143, 24, '2025-05-28 20:33:04'),
(72, 143, 20, '2025-05-28 20:33:07'),
(73, 143, 28, '2025-05-28 20:33:12'),
(74, 143, 53, '2025-05-28 20:33:33'),
(75, 144, 51, '2025-05-28 20:34:08'),
(76, 144, 27, '2025-05-28 20:34:13'),
(77, 144, 24, '2025-05-28 20:34:20'),
(78, 146, 27, '2025-05-28 20:35:16'),
(79, 146, 44, '2025-05-28 20:35:21'),
(80, 146, 53, '2025-05-28 20:35:26'),
(81, 146, 51, '2025-05-28 20:35:30'),
(82, 149, 28, '2025-05-28 20:37:02'),
(83, 149, 20, '2025-05-28 20:37:07'),
(84, 149, 24, '2025-05-28 20:37:10'),
(85, 149, 53, '2025-05-28 20:37:16'),
(86, 150, 51, '2025-05-28 20:37:39'),
(87, 150, 44, '2025-05-28 20:37:49'),
(88, 150, 24, '2025-05-28 20:37:53'),
(89, 152, 39, '2025-05-28 20:38:16'),
(90, 152, 35, '2025-05-28 20:38:20'),
(91, 152, 36, '2025-05-28 20:38:23'),
(92, 152, 37, '2025-05-28 20:38:27'),
(93, 152, 38, '2025-05-28 20:38:31'),
(97, 166, 58, '2025-05-29 06:50:36'),
(98, 166, 57, '2025-05-29 06:50:40'),
(99, 169, 53, '2025-05-29 10:09:09'),
(100, 169, 24, '2025-05-29 10:09:27'),
(101, 169, 20, '2025-05-29 10:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','teacher','admin') DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `major_id` int DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `username_2` (`username`),
  KEY `username_3` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `first_name`, `last_name`, `username`, `reset_token`, `reset_token_expire`, `login_time`, `major_id`, `phone`) VALUES
(7, 'admin@example.com', '$2y$10$j/srsUsmgl/BA3FWBlQVhOKk4b7tKdvahStdBwTwBy4o5t0ImilIq', 'admin', 'Admin', 'User', 'admin', NULL, NULL, '2025-05-10 20:46:37', NULL, NULL),
(116, 'ghadeer20@gmail.com', '$2y$10$NqtcCyJoe5H36tn9I4UBnO84YtXjwPr2PUK7sbsP.bf2y6lNLNuWq', 'teacher', 'Ghadeerr', 'khalaf', 'Ghadeer', NULL, NULL, '2025-05-25 21:50:50', NULL, NULL),
(117, 'ghadeer21@gmail.com', '$2y$10$PdmlRPyQgAly0G6L/jbF5uPBflb5/Cwnb0HwTVGbJBVkvsJt9CR1i', 'teacher', 'Ghadeer', 'khalaf', 'Ghadeer', NULL, NULL, '2025-05-25 22:40:50', NULL, NULL),
(130, 'hala1@gmail.com', '$2y$10$.A2AAjB/iOhA1a2f4FLcZOoOdPhj2Nku.k0QP5FtybeBIsYnCxjK.', 'student', 'Hala', 'Saleh', 'hala', NULL, NULL, '2025-05-27 18:02:15', NULL, '+9617123463'),
(131, 'ayat3@gmail.com', '$2y$10$clDeFGDlFUTHSfWwKr2dt.jyOGxZEg97BuyoCDmurIzJcHaUROy.m', 'teacher', 'ayat', 'khalaf', 'ayat', NULL, NULL, '2025-05-27 18:48:25', NULL, '+96171234635'),
(133, 'ayatkh1@gmail.com', '$2y$10$wgFHLj6V01VqcjuN5InBA.kPKK4YKPdJG.ipTq/GQA3t7.w.Wryxm', 'teacher', 'ayat', 'khalaf', 'ayat', NULL, NULL, '2025-05-27 19:41:36', NULL, '+96171234635'),
(135, 'ayatkh2@gmail.com', '$2y$10$aiZSLW2W7JbALpfm4IES6ObNiOiTd88SnkwZATEOv3G9ouzUVD/NO', 'teacher', 'ayat', 'khalaf', 'ayat', NULL, NULL, '2025-05-27 22:09:53', NULL, '+96171234635'),
(136, 'ayatkh18@gmail.com', '$2y$10$P3RY6/0Q2ztV1vyJyo27vOPbsBiddXWMXfN3LD.R2iM8qFwEQ9Exe', 'teacher', 'Ayat', 'khalaf', 'ayat', NULL, NULL, '2025-05-27 22:21:39', NULL, '+96171234635'),
(137, 'zainab8@gmail.com', '$2y$10$/2RIJwEZBLyvkkwWOSYUbuhfh3EqCorGEFuxojcByyWrLTC51Hmce', 'teacher', 'zainab', 'jomaa', 'zainab', NULL, NULL, '2025-05-27 22:42:16', NULL, '+961712346399'),
(138, 'ghadeer22@gmail.com', '$2y$10$kIoqqOWR5yM8YEl3ExjXC.RZDNdGxzBbcGycZMWoz7FKrYUP7KoiG', 'teacher', 'Ghadeer', 'khalaf', 'ghadeer', NULL, NULL, '2025-05-28 10:18:58', NULL, '+96171234636'),
(140, 'hala@gmail.com', '$2y$10$m6WHGohtbIUCfUW4pnw69O9a2kLn.KXfxkAMVbJ9QWrwyd/5f6sL2', 'teacher', 'hala', 'saleh', 'hala', NULL, NULL, '2025-05-28 10:27:53', NULL, '+96171234452'),
(141, 'mona@gmail.com', '$2y$10$DNc/HyocKfgti8W6fKegyuj.O5mIF2SxYO6Team4yf0mprPmIqE/i', 'teacher', 'mona', 'khalaf', 'mona', NULL, NULL, '2025-05-28 10:29:59', NULL, '+96171234635'),
(143, 'samer@gmail.com', '$2y$10$7DvCuPOJ.LziBHly//5gSu3szyu4PqJYaVN7SSdRv/Ro4fT42ynci', 'teacher', 'samer', 'khalaf', 'samer', NULL, NULL, '2025-05-28 10:31:51', NULL, '+96171894635'),
(144, 'qamar@gmail.com', '$2y$10$61WgjGZcUFaoOSZS8B863.vgwHqADVBEgfrtnWGjixTqGUyEZug1O', 'teacher', 'qamar', 'hassan', 'qamar', NULL, NULL, '2025-05-28 10:33:01', NULL, '+96171894635'),
(146, 'ziad1@gmail.com', '$2y$10$VtD0bMS6pZRXVqw412MlLuwwYan0L/gTju1QMJXTSFOIU3YceNSr.', 'teacher', 'ziad', 'hassan', 'ziad', NULL, NULL, '2025-05-28 10:34:48', NULL, '+96171234452'),
(149, 'sajeda2@gmail.com', '$2y$10$1HdDTPS.B3gYKKgVfJfcK.z7TORe2jFmRLlk18tHKwvC/lT7v6U/e', 'teacher', 'sajeda', 'jomaa', 'sajeda', NULL, NULL, '2025-05-28 10:37:27', NULL, '+96171894635'),
(150, 'sajeda3@gmail.com', '$2y$10$H01LLqzlQTeBLg5WdhbcT.j/DH4Gb7fuWorJ2N9mGOPGX/6o0rGJq', 'teacher', 'sajeda', 'krayem', 'sajeda', NULL, NULL, '2025-05-28 10:38:36', NULL, '+96171894635'),
(152, 'abdo@gmail.com', '$2y$10$jQ8AyrBrIaRxQcsc5NV4BOVCA75UmDt2fmjh6fu9FkNRWkurq1Qj6', 'teacher', 'abdo', 'hassan', 'abdo', NULL, NULL, '2025-05-28 10:41:30', NULL, '+96171234636'),
(153, 'ghadeer@gmail.com', '$2y$10$vkWO09Z9dWqbLMQOR4uwOeQdGbmNU6FzozHZDi4Fyce5ZN1S1x5BC', 'teacher', 'Ghadeer', 'khalaf', 'ghadeer', NULL, NULL, '2025-05-28 10:43:06', NULL, '+96171234636'),
(154, 'aysha@gmail.com', '$2y$10$3RlQ48H66WxbE1oFeajHBeC6XMQySG82EuOd57cSyIBcpmTCKJ6zW', 'student', 'Aysha', 'shayah', 'ayasha', NULL, NULL, '2025-05-28 10:54:43', NULL, '+96171234636'),
(155, 'ghada@gmail.com', '$2y$10$L0y0XkmgaC9Hm.JE7X.o5eEut2atIMuzj8u4wjTN.QoTHcbRYHVf2', 'student', 'Ghada', 'khalaf', 'ghada', NULL, NULL, '2025-05-28 10:55:25', NULL, '+96171234636'),
(156, 'zainab@gmail.com', '$2y$10$jlARwB5hYKXHl1vNe9CbQOFXdc/RaArDr50xpC9ut61khTjp42yci', 'student', 'zainab', 'jomaa', 'zainab', NULL, NULL, '2025-05-28 10:56:00', NULL, '+96171234553'),
(157, 'hiba@gmail.com', '$2y$10$0BMZLYeU2uXAYQkS7mGbJ.eEDdXyJpw/FVrsnOWLIgORat2gPc1fO', 'student', 'hiba', 'bekai', 'hiba', NULL, NULL, '2025-05-28 10:57:05', NULL, '+96171234542'),
(158, 'ahmad@gmail.com', '$2y$10$U5Gegcw9YwuoLt37l7m5u.8GU/hti.vgSKBulPaK5Kf6nnKi56kWK', 'student', 'ahmad', 'saleh', 'ahmad', NULL, NULL, '2025-05-28 10:57:50', NULL, '+96171234636'),
(159, 'mohammad@gmail.com', '$2y$10$l/RXwwpLUe0juF54RNA0oONzP3vN.3eSOdAK8OiJteLmV0WM3aCC.', 'student', 'mohammad', 'jomaa', 'mhmd', NULL, NULL, '2025-05-28 11:00:11', NULL, '+96171234553'),
(160, 'eman@gmail.com', '$2y$10$lwW6Vy/GmcbE00WH/Zf/tev2Wpire7ISy3wbQ4.bwZFytmsZV30la', 'student', 'eman', 'hassan', 'eman', NULL, NULL, '2025-05-28 11:00:51', NULL, '+96171234553'),
(161, 'ziad4@gmail.com', '$2y$10$lTxE3L5yz10RZed9cp7gDeQAnfXOC3LWNCgYyk7nJzVwEs9dlElC6', 'student', 'ziad', 'hassan', 'ziad', NULL, NULL, '2025-05-28 11:01:25', NULL, '+96171234452'),
(162, 'ziad@gmail.com', '$2y$10$ds4ZuEIxOqKTmT7VZ6xwvushgLCh8LbFqalGvDAIL9O4umdkS6BwG', 'student', 'ziad', 'khalaf', 'ziad', NULL, NULL, '2025-05-28 11:02:49', NULL, '+96171234452'),
(163, 'ccmc895@gmail.com', '$2y$10$UPuSydaGrAlPaco12iDi/.p9lsPeBpg9sv/H4iQwQoBi7DKkXwvtq', 'student', 'aya', 'al ahmad', 'aya', NULL, NULL, '2025-05-28 11:04:14', NULL, '+96171234636'),
(164, 'marwa@gmail.com', '$2y$10$gqAfbTU5D1/2ejAZpPiWTOmQZvlE7R4M3JuUP5qSa/TfcTey3aRPa', 'student', 'marwa', 'jomaa', 'marwa', NULL, NULL, '2025-05-28 11:05:23', NULL, '+96171234636'),
(165, 'ghadeerkhalaf@gmail.com', '$2y$10$JfIykeekyUsFiB8Bz62I0ul4tG/7o22PPBv.0GK1zuUDAMxPuMr9q', 'student', 'ghadeer', 'khalaf', 'ghadeer', NULL, NULL, '2025-05-29 09:06:10', NULL, '+96171234452'),
(166, 'ayatkhalaf@gmail.com', '$2y$10$DqIkN0CTsLGIQGlmHUrHrOjjJMnXdRRmGi5GKOciCmQY4o1SalFWe', 'teacher', 'ayat', 'khalaf', 'ayat', NULL, NULL, '2025-05-29 09:24:39', NULL, '+96171234635'),
(167, 'malakhassan@gmail.com', '$2y$10$MlCMkNp1.T84YI2oRDkdkurJ3d87rIuXa2mJ3orsdcN4RjAeHX6ay', 'student', 'malak', 'hassan', 'malak', NULL, NULL, '2025-05-29 13:00:51', NULL, '+96171234635'),
(168, 'ayshashyah@gmail.com', '$2y$10$8T.vx2gEB3u/bidblvhvvu1J0JsTrTkEUFhJhIxQvfWX3ZCkceqhu', 'teacher', 'ayasha', 'shayah', 'aysha', NULL, NULL, '2025-05-29 13:06:11', NULL, '+96171234635'),
(169, 'ayshashyah20@gmail.com', '$2y$10$LkKhKGd05dOuCSMdJuGwgOs9OSxIKMx0LdGGXy53DVy995j4wGvki', 'teacher', 'ayasha', 'shayah', 'aysha', NULL, NULL, '2025-05-29 13:07:36', NULL, '+96171234635');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_ibfk_2` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`);

--
-- Constraints for table `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD CONSTRAINT `course_schedule_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `contact_messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD CONSTRAINT `student_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_applications_ibfk_2` FOREIGN KEY (`major`) REFERENCES `major` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `teacher_applications`
--
ALTER TABLE `teacher_applications`
  ADD CONSTRAINT `teacher_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
