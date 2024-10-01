-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 05:28 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `username` varchar(11) NOT NULL,
  `password` varchar(11) NOT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `role`) VALUES
(1, 'Admin', 'admin', '$2y$10$A6Jx', ''),
(2, 'a', 'a', '$2y$10$/MrW', ''),
(3, 'b', 'b', '$2y$10$lvvg', ''),
(4, 'c', 'c', '$2y$10$uk5A', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(50) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(100) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_name`, `course_code`, `instructor`, `semester`, `status`) VALUES
(1, 'Bachelor of Science in Information System', 'CCV403', 'Jonelle Cenita', 'First Semester', 'Active'),
(2, 'Bachelor of Science in Information System', 'CCV403', 'Jonelle Cenita', 'First Semester', 'Active'),
(3, 'Bachelor of Science in Tourism Management', 'CC4054', 'Sample Teacher', 'First Sem', 'Active'),
(4, 'Bachelor of Science in Accounting in Information System', 'CC403', 'No Instructor', 'First Semester', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `semester_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `course_teachers`
--

CREATE TABLE `course_teachers` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `custom_questions`
--

CREATE TABLE `custom_questions` (
  `id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('rating','text') NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `order_num` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `custom_questions`
--

INSERT INTO `custom_questions` (`id`, `question_text`, `question_type`, `category`, `order_num`, `is_deleted`) VALUES
(8, 'Job Knowledge', 'rating', NULL, NULL, 0),
(9, 'Expertise', 'rating', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `evaluationDate` varchar(100) NOT NULL,
  `evaluator` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`id`, `name`, `subject`, `evaluationDate`, `evaluator`, `teacher_id`, `student_id`) VALUES
(86, 'Lourdes Fernandez', 'Psychology', '2024-09-27', 'a', 5, 3),
(87, 'Ramon Gonzales', 'Physics', '2024-09-27', 'a', 6, 3),
(88, 'Rosario Tan', 'History', '2024-09-27', 'a', 7, 3),
(89, 'Rosario Tan', 'History', '2024-09-27', 'a', 8, 3),
(90, 'teacher', 'values', '2024-09-27', 'a', 9, 3),
(91, 'teacher 2', 'Financial Management', '2024-09-27', 'a', 10, 3),
(92, 'teacher 2', 'Financial Management', '2024-09-27', 'johny', 10, 4),
(93, 'Rosario Tan', 'History', '2024-09-27', 'johny', 8, 4),
(94, 'teacher', 'values', '2024-09-27', 'johny', 9, 4),
(95, 'Ana', 'PE', '2024-09-27', 'a', 12, 3),
(96, 'Rose', 'BSIS', '2024-09-27', 'a', 11, 3),
(97, 'Ana', 'PE', '2024-09-27', 'johny', 12, 4),
(98, 'Rose', 'BSIS', '2024-09-27', 'johny', 11, 4);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_responses`
--

CREATE TABLE `evaluation_responses` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `semester`, `start_date`, `end_date`, `date_created`, `status`) VALUES
(6, 'First Semester', '2024-09-28', '2024-11-27', '2024-09-27 08:45:29', 'active'),
(7, 'Second Semester', '2024-11-27', '2025-01-27', '2024-09-27 09:34:43', 'active'),
(8, 'Summer Semester', '2025-01-27', '2025-06-27', '2024-09-27 10:14:56', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `semester_questions`
--

CREATE TABLE `semester_questions` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `question_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `semester_teachers`
--

CREATE TABLE `semester_teachers` (
  `id` int(11) NOT NULL,
  `semester_id` varchar(11) NOT NULL,
  `teacher_id` varchar(11) NOT NULL,
  `course_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semester_teachers`
--

INSERT INTO `semester_teachers` (`id`, `semester_id`, `teacher_id`, `course_id`) VALUES
(1, '6', '3', '1'),
(2, '6', '4', '3'),
(3, '6', '12', '3'),
(4, '6', '5', ''),
(5, '6', '6', ''),
(6, '6', '7', ''),
(7, '6', '8', ''),
(8, '6', '9', ''),
(9, '6', '10', ''),
(11, '6', '11', ''),
(12, '7', '3', ''),
(13, '8', '6', ''),
(14, '8', '8', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `major` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_table`
--

INSERT INTO `student_table` (`id`, `name`, `major`, `email`, `username`, `password`, `role`) VALUES
(3, 'a', 'a', 'a@gmail.com', 'a', '$2y$10$oE98vggmrfclUTkMH.38NeIOV2//0rgFs85CUpIw2jLD99qy4IKXm', 'student'),
(4, 'johny', 'Programming', 'johny@gmail.com', 'johny', '$2y$10$bVsf0iR6I2Ur5fqwhTzB.eZAVBGu9L4mTZw07d70AbGJBW/BHXcQ6', 'student'),
(5, 'john', 'IT', 'john@gmail.com', 'john', '$2y$10$9JGLJihN0ZcRoGfuM8OSXe/ZKMliPqKy1CclqEBkwyaW1snMjXrE6', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `rating` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `subject`, `username`, `password`, `role`, `rating`) VALUES
(3, 'Ana Reyes', 'English Literature', '', '', '', '4.3'),
(4, 'Jose Garcia', 'Mathematics', '', '', '', '5.0'),
(5, 'Lourdes Fernandez', 'Psychology', '', '', '', '4.8'),
(6, 'Ramon Gonzales', 'Physics', '', '', '', '5.0'),
(7, 'Rosario Tan', 'History', '', '', '', '4.0'),
(8, 'Rosario Tan', 'History', '', '', '', '5.0'),
(9, 'teacher', 'values', '', '', '', '4.8'),
(10, 'teacher 2', 'Financial Management', '', '', '', '4.5'),
(11, 'Rose', 'BSIS', 'rose', '$2y$10$KuAXg2Dy2uHtOModuHr4f.0HEUbQSFNEmyUzMhsxFDnJpXIFzzeR2', '', '5.0'),
(12, 'Ana', 'PE', 'ana', '$2y$10$1CJgGEeYGzeCdbcMKgIqw.TttlRbj0RJgv1LdeBeMJrqf2pCf8uuW', 'teacher', '3.8');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(9, 'neil', '$2y$10$GFXCT7ufuIn6IlCiOo/Ng.91irt7Fs6NtQIbFn.h/i6/w.NveOqG6', 'student'),
(11, 'a', '$2y$10$9cm14YLn3j/IKDBqCmSttubOINFnHthZp/ETVy5NNCahPA1ieEw9y', 'student'),
(13, 'johny', '$2y$10$bVsf0iR6I2Ur5fqwhTzB.eZAVBGu9L4mTZw07d70AbGJBW/BHXcQ6', 'student'),
(14, 'john', '$2y$10$9JGLJihN0ZcRoGfuM8OSXe/ZKMliPqKy1CclqEBkwyaW1snMjXrE6', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `course_teachers`
--
ALTER TABLE `course_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `custom_questions`
--
ALTER TABLE `custom_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`teacher_id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_responses`
--
ALTER TABLE `evaluation_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_id` (`evaluation_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semester_questions`
--
ALTER TABLE `semester_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `semester_teachers`
--
ALTER TABLE `semester_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_teachers`
--
ALTER TABLE `course_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custom_questions`
--
ALTER TABLE `custom_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `evaluation_responses`
--
ALTER TABLE `evaluation_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `semester_questions`
--
ALTER TABLE `semester_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semester_teachers`
--
ALTER TABLE `semester_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`);

--
-- Constraints for table `course_teachers`
--
ALTER TABLE `course_teachers`
  ADD CONSTRAINT `course_teachers_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `course_teachers_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Constraints for table `evaluation_responses`
--
ALTER TABLE `evaluation_responses`
  ADD CONSTRAINT `evaluation_responses_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`id`),
  ADD CONSTRAINT `evaluation_responses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `custom_questions` (`id`);

--
-- Constraints for table `semester_questions`
--
ALTER TABLE `semester_questions`
  ADD CONSTRAINT `semester_questions_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
