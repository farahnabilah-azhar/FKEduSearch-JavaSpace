-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2023 at 02:04 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fkedusearch`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'Cybersecurity'),
(2, 'Network'),
(3, 'Software'),
(4, 'Artificial Intelligence'),
(5, 'Graphic');

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `ComplaintID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FeedbackID` int(11) NOT NULL,
  `ComplaintType` varchar(255) NOT NULL,
  `ComplaintDescription` varchar(255) NOT NULL,
  `ComplaintDateTime` datetime NOT NULL,
  `ComplaintStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`ComplaintID`, `UserID`, `FeedbackID`, `ComplaintType`, `ComplaintDescription`, `ComplaintDateTime`, `ComplaintStatus`) VALUES
(1, 1, 0, 'Unsatisfied Experts Feedback', 'Test', '0000-00-00 00:00:00', 'On Hold'),
(3, 3, 0, 'Unsatisfied Experts Feedback', 'Test', '2023-06-21 17:20:25', 'In Investigation'),
(5, 1, 0, 'Others', 'presentation', '2023-06-22 04:33:39', 'In Investigation'),
(6, 1, 0, 'Unsatisfied Experts Feedback', 'as', '2023-06-22 04:40:10', 'NULL'),
(7, 2, 0, 'Unsatisfied Experts Feedback', 'hi', '2023-06-22 04:55:49', ''),
(8, 3, 0, 'Wrongly Assigned Research Area', 'Test', '2023-06-22 06:49:39', 'In Investigation'),
(9, 6, 0, 'Wrongly Assigned Research Area', 'sa', '2023-06-22 08:27:31', 'On Hold');

-- --------------------------------------------------------

--
-- Table structure for table `expert`
--

CREATE TABLE `expert` (
  `ExpertID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ExpertName` varchar(255) NOT NULL,
  `ExpertResearchArea` varchar(255) NOT NULL,
  `ExpertPublication` varchar(255) NOT NULL,
  `ExpertAcademicStatus` varchar(50) NOT NULL,
  `ExpertCV` varchar(255) NOT NULL,
  `ExpertSocialMedia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expert`
--

INSERT INTO `expert` (`ExpertID`, `UserID`, `ExpertName`, `ExpertResearchArea`, `ExpertPublication`, `ExpertAcademicStatus`, `ExpertCV`, `ExpertSocialMedia`) VALUES
(1, 0, 'NUR FARAH NABILAH BINTI AZHAR', 'Software', '', 'Master', 'cv/NUR FARAH NABILAH BINTI AZHAR.pdf', 'https://www.linkedin.com/'),
(2, 0, 'MUHAMMAD FAIQ NABIL', 'Network, Software', '', 'Bachelor', 'cv/NUR FARAH NABILAH BINTI AZHAR.pdf', 'https://www.linkedin.com/'),
(4, 0, 'SITI SARA', 'Network, Software', '', 'PhD', 'cv/CA20059.pdf', 'https://www.linkedin.com/');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ExpertID` int(11) NOT NULL,
  `PostTitle` varchar(255) NOT NULL,
  `PostContent` varchar(500) NOT NULL,
  `PostCreatedDate` date NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `PostStatus` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`PostID`, `UserID`, `ExpertID`, `PostTitle`, `PostContent`, `PostCreatedDate`, `CategoryID`, `PostStatus`) VALUES
(1, 1, 3, 'ENSA', 'Network Technologies', '2023-06-18', 2, 'revised'),
(2, 1, 3, 'Software', 'try', '2023-06-18', 2, 'accepted'),
(3, 5, 0, 'User Post', 'Cybersecurity', '2023-06-19', 1, 'accepted'),
(5, 3, 0, 'Expert post', 'Software', '2023-06-19', 3, 'accepted'),
(6, 1, 0, 'Test', 'Software', '2023-06-20', 3, 'accepted'),
(7, 1, 0, 'Test', 'Software', '2023-06-20', 3, 'accepted'),
(8, 1, 0, 'test add', 'Cybersecurity', '2023-06-20', 1, 'accepted'),
(9, 5, 0, 'Software', 'None', '2023-06-21', 3, 'accepted'),
(10, 2, 0, 'abc', 'abcd', '2023-06-22', 1, 'accepted'),
(11, 2, 0, 'shalini', 'shal', '2023-06-22', 1, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `publicationauthor`
--

CREATE TABLE `publicationauthor` (
  `ExpertID` int(11) NOT NULL,
  `PublicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating` int(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE `research` (
  `ResearchID` int(100) NOT NULL,
  `ResearchTitle` varchar(100) NOT NULL,
  `ResearchDescription` varchar(100) NOT NULL,
  `ResearchArea` varchar(255) NOT NULL,
  `ResearchDate` date NOT NULL,
  `ResearchType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research`
--

INSERT INTO `research` (`ResearchID`, `ResearchTitle`, `ResearchDescription`, `ResearchArea`, `ResearchDate`, `ResearchType`) VALUES
(3, 'vvv', 'vvw', 'network', '2023-06-30', 'book'),
(4, 'ENSA', 'blabla', 'cybersecurity', '2023-06-18', 'journal');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `UserPassword` varchar(255) NOT NULL,
  `UserEmail` varchar(255) NOT NULL,
  `UserRole` varchar(50) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `UserAcademicStatus` varchar(255) NOT NULL,
  `UserCertificate` varchar(255) NOT NULL,
  `UserQRCode` varchar(255) NOT NULL,
  `UserStatus` varchar(50) NOT NULL,
  `Likes` int(222) NOT NULL,
  `Comment` int(222) NOT NULL,
  `rating` int(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `UserPassword`, `UserEmail`, `UserRole`, `CategoryID`, `UserAcademicStatus`, `UserCertificate`, `UserQRCode`, `UserStatus`, `Likes`, `Comment`, `rating`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', 'admin', 2, 'Bachelor', '', '', 'active', 0, 0, 0),
(2, 'admin', 'admin', 'admin@gmail.com', 'admin', 1, 'Bachelor', 'certificate/6493dbd1dc339_CA20059.pdf', 'qr-user/6493dbd1dc339_CA20059.pdf.png', 'active', 0, 0, 0),
(3, 'user', 'user', 'user@gmail.com', 'user', 2, 'Master', 'certificate/6493dbdf21f77_CA20039_THESIS.pdf', 'qr-user/6493dbdf21f77_CA20039_THESIS.pdf.png', 'active', 0, 0, 0),
(4, 'expertise', 'expertise', 'expertise@gmail.com', 'expertise', 3, 'PhD', 'certificate/649e61416648f_CA20059.pdf', 'qr-user/649e61416648f_CA20059.pdf.png', 'active', 0, 0, 0),
(5, 'ca20039', '1234abcd', 'ca20039@student.ump.edu.my', 'expertise', 4, 'Master', 'certificate/6493dd5685fe4_CA20039_THESIS.pdf', 'qr-user/6493dd5685fe4_CA20039_THESIS.pdf.png', 'active', 0, 0, 0),
(6, 'ca20049', '1234abcd', '123', 'user', 3, 'Master', '6493e94d50371_', 'qr-user/6493e94d50371_.png', 'active', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`ComplaintID`);

--
-- Indexes for table `expert`
--
ALTER TABLE `expert`
  ADD PRIMARY KEY (`ExpertID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`ResearchID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `ComplaintID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expert`
--
ALTER TABLE `expert`
  MODIFY `ExpertID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `research`
--
ALTER TABLE `research`
  MODIFY `ResearchID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
