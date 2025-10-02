-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 06:20 PM
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
-- Database: `centurion high school locker booking system`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminofficer`
--

CREATE TABLE `adminofficer` (
  `recordID` int(11) NOT NULL,
  `adminName` varchar(100) DEFAULT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminofficer`
--

INSERT INTO `adminofficer` (`recordID`, `adminName`, `adminEmail`, `password`) VALUES
(66, 'Juanita Mayday', 'tjeiman@outlook.com', 'passw1'),
(68, 'Admin Tester', 'admin@test.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookingID` int(14) NOT NULL,
  `parentID` bigint(20) DEFAULT NULL,
  `studentSchoolNumber` int(7) NOT NULL,
  `studentName` varchar(100) DEFAULT NULL,
  `studentSurname` varchar(100) DEFAULT NULL,
  `booking_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booked_for` date DEFAULT NULL,
  `recordID` int(11) DEFAULT NULL,
  `lockersID` varchar(10) DEFAULT NULL,
  `studentGrade` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingID`, `parentID`, `studentSchoolNumber`, `studentName`, `studentSurname`, `booking_date`, `booked_for`, `recordID`, `lockersID`, `studentGrade`) VALUES
(24, 5612226663035, 390412, 'James', 'Wilson', '2025-08-10 00:00:00', '2026-06-21', 66, NULL, 'Grade 9'),
(26, 5678912345678, 789654, 'Fiona', 'Willem', '2025-03-27 00:00:00', '2026-04-05', 66, NULL, 'Grade 11'),
(27, 5707284141112, 407505, 'Emily', 'Anderson', '2025-05-03 00:00:00', '2026-06-18', 68, NULL, 'Grade 9'),
(28, 5912127358130, 749847, 'Robert', 'White', '2025-03-28 00:00:00', '2026-02-07', 66, NULL, 'Grade 9'),
(29, 6012217265129, 899951, 'Liam', 'Nkosi', '2025-07-31 00:00:00', '2026-04-26', 68, NULL, 'Grade 8'),
(30, 6104301288034, 868018, 'James', 'Brown', '2025-04-17 00:00:00', '2026-04-08', 66, NULL, 'Grade 10'),
(31, 6120643431826, 761953, 'Sophia', 'Nkosi', '2025-09-15 00:00:00', '2026-06-29', 68, NULL, 'Grade 10'),
(32, 6204032653193, 762563, 'John', 'Taylor', '2025-03-09 00:00:00', '2026-10-18', 66, NULL, 'Grade 11'),
(33, 6301209154077, 582347, 'James', 'Hall', '2025-05-13 00:00:00', '2026-05-16', 68, NULL, 'Grade 8'),
(34, 6305123394192, 902543, 'Alice', 'Johnson', '2025-07-08 00:00:00', '2026-04-01', 66, NULL, 'Grade 11'),
(35, 6602050571067, 611784, 'Jane', 'White', '2025-03-07 00:00:00', '2026-06-02', 68, NULL, 'Grade 8'),
(36, 6603287420165, 611591, 'Liam', 'Hall', '2025-05-01 00:00:00', '2026-06-27', 66, NULL, 'Grade 8'),
(37, 6705260699127, 617741, 'Olivia', 'Johnson', '2025-05-15 00:00:00', '2026-05-24', 68, NULL, 'Grade 12'),
(38, 6711189204048, 248256, 'Liam', 'Nkosi', '2025-09-07 00:00:00', '2026-11-26', 66, NULL, 'Grade 10'),
(39, 6806020888197, 287868, 'David', 'Nkosi', '2025-06-02 00:00:00', '2026-04-14', 68, NULL, 'Grade 8'),
(43, 7807275046086, 908979, 'Emily', 'Brown', '2025-06-13 00:00:00', '2026-04-10', 68, NULL, 'Grade 10'),
(44, 7811225151184, 567866, 'Olivia', 'Johnson', '2025-03-30 00:00:00', '2026-10-12', 66, NULL, 'Grade 9'),
(45, 8090359780982, 508534, 'Emma', 'Smith', '2025-04-14 00:00:00', '2026-06-18', 68, NULL, 'Grade 10'),
(48, 8612145459128, 694925, 'Ava', 'Anderson', '2025-07-09 00:00:00', '2026-04-01', 66, NULL, 'Grade 11'),
(49, 9012129123195, 582349, 'John', 'Hall', '2025-06-05 00:00:00', '2026-04-29', 68, NULL, 'Grade 10'),
(50, 9101735471756, 878977, 'Noah', 'Anderson', '2025-05-29 00:00:00', '2026-06-30', 66, NULL, 'Grade 9'),
(52, 9122667840151, 277788, 'Olivia', 'Nkosi', '2025-08-04 00:00:00', '2026-04-26', 66, NULL, 'Grade 10'),
(53, 9212011817041, 764982, 'Noah', 'Taylor', '2025-05-31 00:00:00', '2026-05-30', 68, NULL, 'Grade 8'),
(54, 9298654199567, 110216, 'Olivia', 'Wilson', '2025-09-13 00:00:00', '2026-06-18', 66, NULL, 'Grade 9'),
(55, 9309202925129, 628670, 'Ethan', 'Smith', '2025-05-14 00:00:00', '2026-08-13', 68, NULL, 'Grade 10'),
(56, 9411277787016, 217985, 'Liam', 'Johnson', '2025-09-20 00:00:00', '2026-01-10', 66, 'L1003', 'Grade 10'),
(58, 9504200125035, 550037, 'Olivia', 'Brown', '2025-08-09 00:00:00', '2026-01-16', 66, 'L1010', 'Grade 10'),
(59, 9505017498148, 453633, 'Emily', 'Hall', '2025-09-13 00:00:00', '2026-03-07', 68, 'L1007', 'Grade 10'),
(60, 9507203452020, 173463, 'Michael', 'Anderson', '2025-05-14 00:00:00', '2026-05-13', 66, 'L905', 'Grade 9'),
(63, 1008113111123, 647927, 'Alice', 'Wilson', '2025-09-18 00:00:00', '2026-06-30', 68, 'L1202', 'Grade 12'),
(64, 2020195031961, 895067, 'Alice', 'Taylor', '2025-07-31 00:00:00', '2026-06-30', 66, 'L914', 'Grade 9'),
(65, 3018701182456, 357874, 'Liam', 'Johnson', '2025-08-04 00:00:00', '2026-06-30', 68, 'L804', 'Grade 8'),
(66, 3060525411426, 528034, 'Jane', 'Anderson', '2025-06-20 00:00:00', '2026-06-30', 66, 'L1107', 'Grade 11'),
(67, 3456278901234, 654321, 'Vincent', 'Mark', '2025-05-27 00:00:00', '2026-06-30', 68, 'L902', 'Grade 9'),
(68, 4108192658003, 366289, 'Liam', 'Anderson', '2025-06-09 00:00:00', '2026-06-30', 66, 'L907', 'Grade 9'),
(69, 4109137427178, 910983, 'Ethan', 'Thomas', '2025-03-30 00:00:00', '2026-06-30', 68, 'L1013', 'Grade 10'),
(71, 4207012946087, 558556, 'Liam', 'Smith', '2025-04-21 00:00:00', '2026-06-30', 68, 'L1011', 'Grade 10'),
(72, 4409125431129, 739637, 'John', 'Nkosi', '2025-08-17 00:00:00', '2026-06-30', 66, 'L911', 'Grade 9'),
(73, 4510283636054, 801075, 'Ava', 'White', '2025-04-30 00:00:00', '2026-06-30', 68, 'L1101', 'Grade 11'),
(74, 4532178901345, 789345, 'Fanie', 'Easme', '2025-09-26 00:00:00', '2026-06-30', 66, 'L1114', 'Grade 11'),
(75, 4608148192183, 977032, 'James', 'Anderson', '2025-03-27 00:00:00', '2026-06-30', 68, 'L1205', 'Grade 11'),
(76, 4609139102037, 801734, 'Olivia', 'Smith', '2025-07-08 00:00:00', '2026-06-30', 66, 'L1203', 'Grade 12'),
(77, 4708037500102, 309568, 'Jane', 'Wilson', '2025-07-25 00:00:00', '2026-06-30', 68, 'L906', 'Grade 9'),
(78, 4803233540087, 382254, 'Michael', 'Nkosi', '2025-07-09 00:00:00', '2026-06-30', 66, 'L805', 'Grade 8'),
(79, 4809018674075, 916977, 'James', 'Taylor', '2025-03-03 00:00:00', '2026-06-30', 68, 'L915', 'Grade 9'),
(81, 4901218928125, 922628, 'Sophia', 'Thomas', '2025-04-07 00:00:00', '2026-06-30', 68, 'L901', 'Grade 9'),
(82, 5004038523192, 200443, 'Ava', 'Anderson', '2025-08-27 00:00:00', '2026-06-30', 66, 'L1103', 'Grade 11'),
(84, 5206163043195, 221375, 'Ava', 'Taylor', '2025-08-02 00:00:00', '2026-06-30', 66, 'L1104', 'Grade 11'),
(85, 5512102556093, 828859, 'Ethan', 'Wilson', '2025-03-21 00:00:00', '2026-06-30', 68, 'L1204', 'Grade 12'),
(86, 7110115033116, 167589, 'Emma', 'Smith', '2025-04-28 00:00:00', '2026-06-30', 66, 'L904', 'Grade 9'),
(97, 3060525411426, 123456, NULL, NULL, '2025-09-30 15:23:53', '2026-10-14', NULL, NULL, 'Grade 9');

-- --------------------------------------------------------

--
-- Table structure for table `lockers`
--

CREATE TABLE `lockers` (
  `lockersID` varchar(10) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `grade` int(3) NOT NULL,
  `assigned` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lockers`
--

INSERT INTO `lockers` (`lockersID`, `location`, `grade`, `assigned`) VALUES
('L1001', 'Block B - Row 1', 10, 'Yes'),
('L1002', 'Block B - Row 1', 10, 'Yes'),
('L1003', 'Block B - Row 1', 10, 'Yes'),
('L1004', 'Block B - Row 2', 10, 'No'),
('L1005', 'Block B - Row 2', 10, 'No'),
('L1006', 'Block B - Row 2', 10, 'No'),
('L1007', 'Block B - Row 3', 10, 'Yes'),
('L1008', 'Block B - Row 3', 10, 'No'),
('L1009', 'Block B - Row 3', 10, 'No'),
('L1010', 'Block B - Row 4', 10, 'Yes'),
('L1011', 'Block B - Row 4', 10, 'Yes'),
('L1012', 'Block B - Row 4', 10, 'No'),
('L1013', 'Block B - Row 5', 10, 'Yes'),
('L1014', 'Block B - Row 5', 10, 'No'),
('L1015', 'Block B - Row 5', 10, 'No'),
('L1101', 'Block D - Row 1', 11, 'Yes'),
('L1102', 'Block D - Row 1', 11, 'No'),
('L1103', 'Block D - Row 1', 11, 'Yes'),
('L1104', 'Block D - Row 2', 11, 'Yes'),
('L1105', 'Block D - Row 2', 11, 'No'),
('L1106', 'Block D - Row 2', 11, 'No'),
('L1107', 'Block D - Row 3', 11, 'Yes'),
('L1108', 'Block D - Row 3', 11, 'No'),
('L1109', 'Block D - Row 3', 11, 'No'),
('L1110', 'Block D - Row 4', 11, 'No'),
('L1111', 'Block D - Row 4', 11, 'No'),
('L1112', 'Block D - Row 5', 11, 'No'),
('L1113', 'Block D - Row 5', 11, 'No'),
('L1114', 'Block D - Row 5', 11, 'Yes'),
('L1115', 'Block D - Row 6', 11, 'No'),
('L1201', 'Block E - Row 1', 12, 'No'),
('L1202', 'Block E - Row 1', 12, 'Yes'),
('L1203', 'Block E - Row 1', 12, 'Yes'),
('L1204', 'Block E - Row 2', 12, 'Yes'),
('L1205', 'Block E - Row 2', 12, 'Yes'),
('L801', 'Block A - Row 1', 8, 'Yes'),
('L802', 'Block A - Row 1', 8, 'Yes'),
('L803', 'Block A - Row 1', 8, 'Yes'),
('L804', 'Block A - Row 2', 8, 'Yes'),
('L805', 'Block A - Row 2', 8, 'Yes'),
('L806', 'Block A - Row 2', 8, 'Yes'),
('L807', 'Block A - Row 3', 8, 'Yes'),
('L808', 'Block A - Row 3', 8, 'Yes'),
('L809', 'Block A - Row 3', 8, 'No'),
('L810', 'Block A - Row 4', 8, 'No'),
('L901', 'Block C - Row 1', 9, 'Yes'),
('L902', 'Block C - Row 1', 9, 'Yes'),
('L903', 'Block C - Row 1', 9, 'No'),
('L904', 'Block C - Row 2', 9, 'Yes'),
('L905', 'Block C - Row 2', 9, 'Yes'),
('L906', 'Block C - Row 2', 9, 'Yes'),
('L907', 'Block C - Row 3', 9, 'Yes'),
('L908', 'Block C - Row 3', 9, 'No'),
('L909', 'Block C - Row 3', 9, 'No'),
('L910', 'Block C - Row 4', 9, 'No'),
('L911', 'Block C - Row 4', 9, 'Yes'),
('L912', 'Block C - Row 4', 9, 'No'),
('L913', 'Block C - Row 5', 9, 'No'),
('L914', 'Block C - Row 5', 9, 'Yes'),
('L915', 'Block C - Row 1', 9, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parentID` bigint(20) NOT NULL,
  `parentTitle` varchar(4) NOT NULL,
  `parentName` varchar(100) NOT NULL,
  `parentSurname` varchar(100) NOT NULL,
  `parentEmail` varchar(100) NOT NULL,
  `homeAddress` varchar(255) DEFAULT NULL,
  `phoneNumber` int(14) DEFAULT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parentID`, `parentTitle`, `parentName`, `parentSurname`, `parentEmail`, `homeAddress`, `phoneNumber`, `password`) VALUES
(1008113111123, 'Mr', 'Ethan', 'Wilson', 'ethan.wilson@gmail.com', '636 Cedar Street', 737248373, 'pass@164'),
(2020195031961, 'Ms', 'Jane', 'Taylor', 'jane.taylor@gmail.com', '911 Ash Street', 781998910, 'pass@158'),
(3018701182456, 'Mrs', 'Mary', 'Johnson', 'mary.johnson@gmail.com', '456 Oak Avenue', 832345678, 'pass@124'),
(3060525411426, 'Dr', 'Emma', 'Anderson', 'emma.anderson@gmail.com', '704 Willow Street', 721206388, 'pass@138'),
(3456278901234, 'Mr', 'Daniel', 'Mark', 'daniel.mark@gmail.com', '113 Saby Avenue', 781234567, 'pass@140'),
(4108192658003, 'Mrs', 'Olivia', 'Anderson', 'olivia.anderson@gmail.com', '404 Cedar Street', 714962168, 'pass@180'),
(4109137427178, 'Mr', 'James', 'Thomas', 'james.thomas@gmail.com', '246 Redwood Court', 789012345, 'pass@130'),
(4204287904104, 'Mr', 'Liam', 'Nkosi', 'liam.nkosi@gmail.com', '433 Birch Street', 744083306, 'pass@143'),
(4207012946087, 'Ms', 'Emma', 'Smith', 'emma.smith@gmail.com', '718 Ash Street', 749100605, 'pass@133'),
(4409125431129, 'Dr', 'John', 'Nkosi', 'john.nkosi@gmail.com', '436 Elm Street', 799536039, 'pass@151'),
(4510283636054, 'Dr', 'Noah', 'White', 'noah.white@gmail.com', '604 Pine Street', 777393801, 'pass@149'),
(4532178901345, 'Ms', 'Tania', 'Easme', 'tania.easme@gmail.com', '234 Florida Avevue', 791234560, 'pass@000'),
(4608148192183, 'Dr', 'John', 'Anderson', 'john.anderson@gmail.com', '789 Willow Street', 777769694, 'pass@171'),
(4609139102037, 'Mrs', 'Olivia', 'Smith', 'olivia.smith@gmail.com', '216 Oak Street', 763380717, 'pass@177'),
(4708037500102, 'Mr', 'Ethan', 'Wilson', 'ethan.wilson@gmail.com', '500 Birch Street', 718425293, 'pass@176'),
(4803233540087, 'Mr', 'Ethan', 'Nkosi', 'ethan.nkosi@gmail.com', '336 Willow Street', 722944641, 'pass@166'),
(4809018674075, 'Mrs', 'Robert', 'Taylor', 'robert.taylor@gmail.com', '162 Ash Street', 743877000, 'pass@145'),
(4809209872196, 'Dr', 'Robert', 'Smith', 'robert.smith@gmail.com', '757 Cedar Street', 768346836, 'pass@142'),
(4901218928125, 'Dr', 'Michael', 'Thomas', 'michael.thomas@gmail.com', '104 Elm Street', 711790361, 'pass@173'),
(5004038523192, 'Ms', 'Linda', 'Anderson', 'linda.anderson@gmail.com', '135 Spruce Street', 778901234, 'pass@129'),
(5106123937064, 'Mrs', 'Alice', 'Smith', 'alice.smith@gmail.com', '447 Birch Street', 759764067, 'pass@148'),
(5206163043195, 'Mr', 'David', 'Taylor', 'david.taylor@gmail.com', '744 Maple Street', 749909129, 'pass@174'),
(5512102556093, 'Mr', 'Liam', 'Wilson', 'liam.wilson@gmail.com', '413 Ash Street', 748585981, 'pass@159'),
(5612226663035, 'Mr', 'Robert', 'Wilson', 'robert.wilson@gmail.com', '189 Pine Street', 735460310, 'pass@131'),
(5678901234567, 'Mrs', 'Daisy', 'Emma', 'daisy.emma@gmail.com', '134 Doornkluf Avenue', 717654321, 'pass@345'),
(5678912345678, 'Mrs', 'Joe', 'Willem', 'joe.willem@gmail.com', '345 Fouries Avenue', 781234567, 'pass@145'),
(5707284141112, 'Mr', 'Liam', 'Anderson', 'liam.anderson@gmail.com', '327 Redwood Street', 796115093, 'pass@146'),
(5912127358130, 'Mr', 'Michael', 'White', 'michael.white@gmail.com', '238 Oak Street', 743043868, 'pass@153'),
(6012217265129, 'Mr', 'John', 'Nkosi', 'john.nkosi@gmail.com', '600 Elm Street', 728680821, 'pass@155'),
(6104301288034, 'Ms', 'Emily', 'Brown', 'emily.brown@gmail.com', '906 Maple Avenue', 719919743, 'pass@178'),
(6120643431826, 'Dr', 'Ethan', 'Nkosi', 'ethan.nkosi@gmail.com', '852 Cedar Street', 752005034, 'pass@144'),
(6204032653193, 'Mr', 'Michael', 'Taylor', 'michael.taylor@gmail.com', '701 Spruce Street', 721216884, 'pass@172'),
(6301209154077, 'Mrs', 'Olivia', 'Hall', 'olivia.hall@gmail.com', '864 Cedar Street', 717403055, 'pass@169'),
(6305123394192, 'Ms', 'Emma', 'Johnson', 'emma.johnson@gmail.com', '715 Birch Street', 763177499, 'pass@152'),
(6602050571067, 'Ms', 'Liam', 'White', 'liam.white@gmail.com', '842 Birch Street', 737988829, 'pass@136'),
(6603287420165, 'Mrs', 'Sophia', 'Hall', 'sophia.hall@gmail.com', '776 Cedar Street', 778621486, 'pass@154'),
(6705260699127, 'Dr', 'David', 'Johnson', 'david.johnson@gmail.com', '473 Birch Street', 784780625, 'pass@163'),
(6711189204048, 'Mr', 'Ethan', 'Nkosi', 'ethan.nkosi@gmail.com', '382 Elm Street', 738510987, 'pass@168'),
(6806020888197, 'Mr', 'Michael', 'Nkosi', 'michael.nkosi@gmail.com', '897 Cedar Street', 798801632, 'pass@140'),
(7009119979038, 'Mrs', 'Ava', 'Wilson', 'ava.wilson@gmail.com', '911 Birch Street', 764281373, 'pass@147'),
(7110115033116, 'Mr', 'John', 'Smith', 'john.smith@gmail.com', '123 Maple Street', 821234567, 'pass@123'),
(7303154126003, 'Dr', 'Ethan', 'Taylor', 'ethan.taylor@gmail.com', '404 Oak Street', 799314784, 'pass@157'),
(7807275046086, 'Dr', 'Liam', 'Brown', 'liam.brown@gmail.com', '492 Spruce Street', 786828544, 'pass@141'),
(7811225151184, 'Mrs', 'Emma', 'Johnson', 'emma.johnson@gmail.com', '483 Oak Street', 787978403, 'pass@170'),
(8090359780982, 'Mr', 'John', 'Smith', 'john.smith@gmail.com', '140 Maple Street', 775223322, 'pass@161'),
(8305068147131, 'Mrs', 'Ava', 'Smith', 'ava.smith@gmail.com', '599 Spruce Street', 783072551, 'pass@134'),
(8502022675077, 'Ms', 'Jane', 'Anderson', 'jane.anderson@gmail.com', '431 Spruce Street', 758356276, 'pass@150'),
(8612145459128, 'Dr', 'Robert', 'Anderson', 'robert.anderson@gmail.com', '991 Redwood Street', 764100813, 'pass@179'),
(9002035199083, 'Mrs', 'Daisy', 'Morapedi', 'daisy.morapedi@gmail.com', '111 Denzel Street', 810099001, 'pass@178'),
(9012129123195, 'Mr', 'Noah', 'Hall', 'noah.hall@gmail.com', '190 Willow Street', 743682129, 'pass@137'),
(9101735471756, 'Mr', 'Michael', 'Anderson', 'michael.anderson@gmail.com', '702 Maple Street', 775510858, 'pass@132'),
(9108286769008, 'Mr', 'Noah', 'Taylor', 'noah.taylor@gmail.com', '566 Ash Street', 787273081, 'pass@175'),
(9122667840151, 'Mr', 'Noah', 'Nkosi', 'noah.nkosi@gmail.com', '353 Oak Street', 763407066, 'pass@165'),
(9212011817041, 'Dr', 'Robert', 'Taylor', 'robert.taylor@gmail.com', '987 Birch Boulevard', 767890123, 'pass@128'),
(9298654199567, 'Mr', 'Emma', 'Wilson', 'emma.wilson@gmail.com', '654 Cedar Drive', 756789012, 'pass@127'),
(9309202925129, 'Mr', 'Liam', 'Smith', 'liam.smith@gmail.com', '310 Maple Street', 742749180, 'pass@160'),
(9411277787016, 'Mr', 'David', 'Johnson', 'david.johnson@gmail.com', '321 Maple Lane', 745678901, 'pass@126'),
(9501303848045, 'Mrs', 'Ava', 'Wilson', 'ava.wilson@gmail', '487 Spruce Street', 714946067, 'pass@167'),
(9504200125035, 'Ms', 'Linda', 'Brown', 'linda.brown@gmail.com', '789 Pine Road', 843456789, 'pass@125'),
(9505017498148, 'Mrs', 'Ava', 'Hall', 'ava.hall@gmail.com', '332 Maple Street', 791798774, 'pass@135'),
(9507203452020, 'Mr', 'Robert', 'Anderson', 'robert.anderson@gmail.com', '875 Maple Street', 739094911, 'pass@139'),
(9602046538016, 'Ms', 'Ava', 'Thomas', 'ava.thomas@gmail.com', '446 Redwood Street', 711846135, 'pass@156'),
(9912089374198, 'Ms', 'Sophia', 'Hall', 'sophia.hall@gmail.com', '661 Maple Street', 777667807, 'pass@162');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
  `FilePath` varchar(255) DEFAULT NULL,
  `studentSchoolNumber` int(11) NOT NULL,
  `parentID` bigint(20) NOT NULL,
  `amount` decimal(6,2) DEFAULT 100.00,
  `paymentStatus` enum('Pending','Paid') DEFAULT 'Pending',
  `paymentDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentSchoolNumber` int(7) NOT NULL,
  `studentName` varchar(100) NOT NULL,
  `studentSurname` varchar(100) NOT NULL,
  `studentGrade` varchar(100) NOT NULL,
  `parentID` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentSchoolNumber`, `studentName`, `studentSurname`, `studentGrade`, `parentID`) VALUES
(100800, 'Georgina', 'Morapedi', 'Grade 9', 9002035199083),
(110216, 'Olivia', 'Wilson', 'Grade 9', 9298654199567),
(123456, 'Georgina', 'Anderson', 'Grade 9', 3060525411426),
(163680, 'Emma', 'Wilson', 'Grade 8', 9501303848045),
(167589, 'Emma', 'Smith', 'Grade 9', 7110115033116),
(173463, 'Michael', 'Anderson', 'Grade 9', 9507203452020),
(200443, 'Ava', 'Anderson', 'Grade 11', 5004038523192),
(217985, 'Liam', 'Johnson', 'Grade 10', 9411277787016),
(221375, 'Ava', 'Taylor', 'Grade 11', 5206163043195),
(222444, 'Fanie', 'Wilson', 'Grade 11', 1008113111123),
(248256, 'Liam', 'Nkosi', 'Grade 10', 6711189204048),
(264399, 'Robert', 'Thomas', 'Grade 8', 9602046538016),
(271520, 'Alice', 'Smith', 'Grade 8', 4809209872196),
(277788, 'Olivia', 'Nkosi', 'Grade 10', 9122667840151),
(287868, 'David', 'Nkosi', 'Grade 8', 6806020888197),
(300100, 'Vincent', 'Morapedi', 'Grade 10', 9002035199083),
(309568, 'Jane', 'Wilson', 'Grade 9', 4708037500102),
(312754, 'Liam', 'Smith', 'Grade 10', 5106123937064),
(317750, 'Ava', 'Smith', 'Grade 10', 8305068147131),
(357874, 'Liam', 'Johnson', 'Grade 8', 3018701182456),
(366289, 'Liam', 'Anderson', 'Grade 9', 4108192658003),
(382254, 'Michael', 'Nkosi', 'Grade 8', 4803233540087),
(390412, 'James', 'Wilson', 'Grade 9', 5612226663035),
(407505, 'Emily', 'Anderson', 'Grade 9', 5707284141112),
(411800, 'Davon', 'Morapedi', 'Grade 10', 9002035199083),
(422522, 'James', 'Serengeti', 'Grade 11', NULL),
(453633, 'Emily', 'Hall', 'Grade 10', 9505017498148),
(508534, 'Emma', 'Smith', 'Grade 10', 8090359780982),
(509342, 'Olivia', 'Taylor', 'Grade 8', 7303154126003),
(528034, 'Jane', 'Anderson', 'Grade 11', 3060525411426),
(533655, 'Tiny', 'White', 'Grade 11', 6602050571067),
(540787, 'Sophia', 'Taylor', 'Grade 8', 9108286769008),
(550037, 'Olivia', 'Brown', 'Grade 10', 9504200125035),
(558556, 'Liam', 'Smith', 'Grade 10', 4207012946087),
(567866, 'Olivia', 'Johnson', 'Grade 9', 7811225151184),
(582347, 'James', 'Hall', 'Grade 8', 6301209154077),
(582349, 'John', 'Hall', 'Grade 10', 9012129123195),
(611591, 'Liam', 'Hall', 'Grade 8', 6603287420165),
(611784, 'Jane', 'White', 'Grade 8', 6602050571067),
(617741, 'Olivia', 'Johnson', 'Grade 12', 6705260699127),
(628670, 'Ethan', 'Smith', 'Grade 10', 9309202925129),
(647927, 'Alice', 'Wilson', 'Grade 12', 1008113111123),
(652308, 'Michael', 'Wilson', 'Grade 10', 7009119979038),
(654321, 'Vincent', 'Mark', 'Grade 9', 3456278901234),
(694925, 'Ava', 'Anderson', 'Grade 11', 8612145459128),
(720053, 'Jane', 'Nkosi', 'Grade 10', 4204287904104),
(739637, 'John', 'Nkosi', 'Grade 9', 4409125431129),
(749847, 'Robert', 'White', 'Grade 9', 5912127358130),
(761953, 'Sophia', 'Nkosi', 'Grade 10', 6120643431826),
(762563, 'John', 'Taylor', 'Grade 11', 6204032653193),
(764982, 'Noah', 'Taylor', 'Grade 8', 9212011817041),
(773539, 'John', 'Hall', 'Grade 8', 9912089374198),
(787900, 'Aidan', 'Taylor', 'Grade 10', 2020195031961),
(789345, 'Fanie', 'Easme', 'Grade 11', 4532178901345),
(789654, 'Fiona', 'Willem', 'Grade 11', 5678912345678),
(801075, 'Ava', 'White', 'Grade 11', 4510283636054),
(801734, 'Olivia', 'Smith', 'Grade 12', 4609139102037),
(825753, 'Ethan', 'Anderson', 'Grade 8', 8502022675077),
(828859, 'Ethan', 'Wilson', 'Grade 12', 5512102556093),
(868018, 'James', 'Brown', 'Grade 10', 6104301288034),
(878977, 'Noah', 'Anderson', 'Grade 9', 9101735471756),
(895067, 'Alice', 'Taylor', 'Grade 9', 2020195031961),
(899951, 'Liam', 'Nkosi', 'Grade 8', 6012217265129),
(902543, 'Alice', 'Johnson', 'Grade 11', 6305123394192),
(908979, 'Emily', 'Brown', 'Grade 10', 7807275046086),
(910983, 'Ethan', 'Thomas', 'Grade 10', 4109137427178),
(916977, 'James', 'Taylor', 'Grade 9', 4809018674075),
(922628, 'Sophia', 'Thomas', 'Grade 9', 4901218928125),
(977032, 'James', 'Anderson', 'Grade 11', 4608148192183);

-- --------------------------------------------------------

--
-- Table structure for table `waiting_list`
--

CREATE TABLE `waiting_list` (
  `waitingID` int(11) NOT NULL,
  `studentSchoolNumber` int(7) NOT NULL,
  `studentName` varchar(100) DEFAULT NULL,
  `studentSurname` varchar(100) DEFAULT NULL,
  `parentID` bigint(20) NOT NULL,
  `requestedGrade` varchar(20) NOT NULL,
  `appliedDate` datetime DEFAULT current_timestamp(),
  `status` enum('Awaiting Allocation','Waitlisted','Allocated','Cancelled') DEFAULT 'Awaiting Allocation',
  `adminNote` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waiting_list`
--

INSERT INTO `waiting_list` (`waitingID`, `studentSchoolNumber`, `studentName`, `studentSurname`, `parentID`, `requestedGrade`, `appliedDate`, `status`, `adminNote`) VALUES
(3, 652308, 'Michael', 'Wilson', 7009119979038, 'Grade 10', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(4, 509342, 'Olivia', 'Taylor', 7303154126003, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(5, 317750, 'Ava', 'Smith', 8305068147131, 'Grade 10', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(6, 825753, 'Ethan', 'Anderson', 8502022675077, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(7, 540787, 'Sophia', 'Taylor', 9108286769008, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(8, 163680, 'Emma', 'Wilson', 9501303848045, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(9, 773539, 'John', 'Hall', 9912089374198, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(10, 720053, 'Jane', 'Nkosi', 4204287904104, 'Grade 10', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(11, 271520, 'Alice', 'Smith', 4809209872196, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(12, 312754, 'Liam', 'Smith', 5106123937064, 'Grade 10', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(13, 264399, 'Robert', 'Thomas', 9602046538016, 'Grade 8', '2025-08-10 12:30:40', 'Awaiting Allocation', NULL),
(14, 222444, 'Fanie', 'Wilson', 1008113111123, 'Grade 11', '2025-09-26 17:06:21', 'Awaiting Allocation', NULL),
(15, 787900, 'Aidan', 'Taylor', 2020195031961, 'Grade 10', '2025-09-26 17:35:54', 'Awaiting Allocation', NULL),
(16, 123456, NULL, NULL, 3060525411426, 'Grade 9', '2025-09-30 15:23:53', 'Awaiting Allocation', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminofficer`
--
ALTER TABLE `adminofficer`
  ADD PRIMARY KEY (`recordID`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookingID`),
  ADD UNIQUE KEY `unique_locker_assignment` (`lockersID`),
  ADD KEY `parentID` (`parentID`),
  ADD KEY `recordID` (`recordID`),
  ADD KEY `lockersID` (`lockersID`),
  ADD KEY `lockersID_2` (`lockersID`);

--
-- Indexes for table `lockers`
--
ALTER TABLE `lockers`
  ADD PRIMARY KEY (`lockersID`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parentID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `studentSchoolNumber` (`studentSchoolNumber`),
  ADD KEY `parentID` (`parentID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentSchoolNumber`),
  ADD KEY `parentID` (`parentID`);

--
-- Indexes for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`waitingID`),
  ADD KEY `studentSchoolNumber` (`studentSchoolNumber`),
  ADD KEY `parentID` (`parentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminofficer`
--
ALTER TABLE `adminofficer`
  MODIFY `recordID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookingID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `waitingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`recordID`) REFERENCES `adminofficer` (`recordID`),
  ADD CONSTRAINT `fk_bookings_lockersID` FOREIGN KEY (`lockersID`) REFERENCES `lockers` (`lockersID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`studentSchoolNumber`) REFERENCES `student` (`studentSchoolNumber`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`);

--
-- Constraints for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD CONSTRAINT `fk_wait_parent` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wait_student` FOREIGN KEY (`studentSchoolNumber`) REFERENCES `student` (`studentSchoolNumber`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
