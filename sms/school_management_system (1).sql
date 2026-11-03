-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2026 at 07:15 AM
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
-- Database: `school_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `school_id`, `username`, `password`, `full_name`, `created_at`) VALUES
(2, 2, 'admin', '$2y$10$nfRvkk3.9TeoJTVZIVva9ucCHmhC4zTShYCHJSh8xq8Xqq.pSK7UW', 'Khyrk Shan Gula', '2026-03-16 03:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_name` varchar(50) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `school_year` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `school_id`, `teacher_id`, `class_name`, `section_name`, `grade_level`, `school_year`) VALUES
(2, 2, 1, 'Basic Education', 'Ephisians', 12, '2025-2026'),
(3, 2, 3, 'Preschool', 'HAHA', 0, '2025-2026'),
(4, 2, 4, 'Basic Education', 'zeus', 11, '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int(11) NOT NULL,
  `school_id_code` varchar(20) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_type` enum('Public','Private') DEFAULT 'Public',
  `school_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`school_id`, `school_id_code`, `school_name`, `school_type`, `school_address`, `created_at`) VALUES
(2, '43211', 'Ramon Torres National High School Senior High ', 'Public', 'Balingasag , Bago City , Negros Occidental', '2026-03-16 03:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `lrn` varchar(12) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `birthdate` date NOT NULL,
  `status` enum('Active','Transferred','Dropped') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `class_id`, `school_id`, `lrn`, `first_name`, `last_name`, `middle_name`, `gender`, `birthdate`, `status`, `created_at`) VALUES
(1, 2, 2, '117504060178', 'Khyrk Shan', 'Gula ', 'Yudo', 'Male', '2002-02-11', 'Active', '2026-03-16 03:43:47'),
(2, 2, 2, '402934244934', 'Christian', 'Magtulis', 'y', 'Male', '2006-06-14', 'Active', '2026-03-16 06:43:29'),
(6, 2, 2, '238236612312', 'shan', 'gula', 'y', 'Male', '2009-01-13', 'Active', '2026-03-24 03:44:28'),
(7, 2, 2, '415151511151', 'dsdssd', 'wewewew', 'wwewew', 'Male', '2003-01-01', 'Active', '2026-03-24 03:51:58'),
(9, 3, 2, '121238333333', 'khally shan', 'gula', 'yudo', 'Female', '2026-03-31', 'Active', '2026-03-30 01:42:02'),
(12, NULL, 2, '255151116513', 'gddggd', 'Gula', 'Shan', 'Male', '2007-01-30', 'Active', '2026-04-06 03:19:12'),
(13, 2, 2, '231232234324', 'jdksfjdf', 'dkfjdslfsjfl', 'ksjsdfkldsf', 'Male', '2015-02-10', 'Active', '2026-04-06 03:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `student_records`
--

CREATE TABLE `student_records` (
  `record_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `date_uploaded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_records`
--

INSERT INTO `student_records` (`record_id`, `student_id`, `uploaded_by`, `grade_level`, `document_type`, `file_name`, `file_path`, `date_uploaded`) VALUES
(2, 6, 1, 0, 'PSA Birth Certificate', '238236612312_PSA_Birth_Certificate_1774323868.png', '../uploads/documents/238236612312_PSA_Birth_Certificate_1774323868.png', '2026-03-24 03:44:28'),
(3, 6, 1, 0, 'ECD Checklist', '238236612312_ECD_Checklist_1774323868.docx', '../uploads/documents/238236612312_ECD_Checklist_1774323868.docx', '2026-03-24 03:44:28'),
(4, 7, 1, 0, 'PSA Birth Certificate', '415151511151_PSA_Birth_Certificate_1774324318.png', '../uploads/documents/415151511151_PSA_Birth_Certificate_1774324318.png', '2026-03-24 03:51:58'),
(6, 9, 3, 0, 'PSA Birth Certificate', '121238333333_PSA_Birth_Certificate_1774834922.png', '../uploads/documents/121238333333_PSA_Birth_Certificate_1774834922.png', '2026-03-30 01:42:02'),
(7, 9, 3, 0, 'ECD Checklist', '121238333333_ECD_Checklist_1774834922.png', '../uploads/documents/121238333333_ECD_Checklist_1774834922.png', '2026-03-30 01:42:02'),
(8, 9, 3, 0, 'Progress Report Card', '121238333333_Progress_Report_Card_1774834922.docx', '../uploads/documents/121238333333_Progress_Report_Card_1774834922.docx', '2026-03-30 01:42:02'),
(11, 9, 3, 0, 'Literacy Numeracy Checklist', '', '../uploads/documents/STU_9_Literacy_Numeracy_Checklist_G0_1774837076.jpg', '2026-03-30 02:17:56'),
(12, 9, 3, 0, 'Moving Up Certificate', '', '../uploads/documents/STU_9_Moving_Up_Certificate_G0_1774837094.pdf', '2026-03-30 02:18:14'),
(13, 1, 1, 0, 'PSA Birth Certificate', '', '../uploads/documents/STU_1_PSA_Birth_Certificate_G0_1775445126.pdf', '2026-04-06 03:12:06'),
(14, 1, 1, 0, 'ECD Checklist', '', '../uploads/documents/STU_1_ECD_Checklist_G0_1775445167.pdf', '2026-04-06 03:12:47'),
(15, 1, 1, 0, 'Progress Report Card', '', '../uploads/documents/STU_1_Progress_Report_Card_G0_1775445181.pdf', '2026-04-06 03:13:01'),
(16, 1, 1, 0, 'Literacy Numeracy Checklist', '', '../uploads/documents/STU_1_Literacy_Numeracy_Checklist_G0_1775445195.pdf', '2026-04-06 03:13:15'),
(17, 1, 1, 0, 'Moving Up Certificate', '', '../uploads/documents/STU_1_Moving_Up_Certificate_G0_1775445207.pdf', '2026-04-06 03:13:27'),
(18, 1, 1, 12, 'PSA Birth Certificate', '', '../uploads/documents/STU_1_PSA_Birth_Certificate_G12_1775445218.pdf', '2026-04-06 03:13:38'),
(19, 1, 1, 12, 'School Form 5', '', '../uploads/documents/STU_1_School_Form_5_G12_1775445226.pdf', '2026-04-06 03:13:46'),
(20, 1, 1, 12, 'School Form 9', '', '../uploads/documents/STU_1_School_Form_9_G12_1775445235.pdf', '2026-04-06 03:13:55'),
(21, 1, 1, 12, 'School Form 10', '', '../uploads/documents/STU_1_School_Form_10_G12_1775445243.pdf', '2026-04-06 03:14:03');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `school_id`, `username`, `password`, `full_name`, `status`, `created_at`) VALUES
(1, 2, 'teacher', '$2y$10$0AC3gzgFTmr9VntwqUF5nugQ5z0IQSdx95cCiWlvQlftbYOREZYjS', 'Ronnie Gillang Jr', 'Active', '2026-03-16 03:35:40'),
(2, 2, 'leo', '$2y$10$CqYEdkyU5LnHhsa7EW7cxO6n7UGTpdjxW9MEhtrKvDwWc7nv9nD4u', 'Leo Niedes', 'Active', '2026-03-16 06:21:16'),
(3, 2, 'shan', '$2y$10$bjwUqoBSO8.8Uf4XiFubOeanKv5mn.DcMb06yCIzVYfN15cJu8tEO', 'Khyrk Shan Gula', 'Active', '2026-03-30 01:38:53'),
(4, 2, 'grade11', '$2y$10$pJXGDX1nc2VqarhErxi7fOQlt6MuDsRITxwmLFfHM8TC9mM/VhRHW', 'grade11', 'Active', '2026-04-06 03:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class`
--

CREATE TABLE `teacher_class` (
  `teacher_class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `subject_name` varchar(100) DEFAULT 'Advisory'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_admin_school` (`school_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `fk_teacher` (`teacher_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`),
  ADD UNIQUE KEY `school_id_code` (`school_id_code`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `student_records`
--
ALTER TABLE `student_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `student_records_teacher_fk` (`uploaded_by`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_teacher_school` (`school_id`);

--
-- Indexes for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD PRIMARY KEY (`teacher_class_id`),
  ADD KEY `fk_tc_teacher` (`teacher_id`),
  ADD KEY `fk_tc_class` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_records`
--
ALTER TABLE `student_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teacher_class`
--
ALTER TABLE `teacher_class`
  MODIFY `teacher_class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admin_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_records`
--
ALTER TABLE `student_records`
  ADD CONSTRAINT `student_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_records_teacher_fk` FOREIGN KEY (`uploaded_by`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teacher_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD CONSTRAINT `fk_tc_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tc_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
