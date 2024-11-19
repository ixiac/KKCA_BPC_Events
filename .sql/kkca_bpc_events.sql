-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 08:02 AM
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
-- Database: `kkca_bpc_events`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AID` varchar(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `sex` int(1) NOT NULL,
  `address` text NOT NULL,
  `tel_no` text NOT NULL,
  `age` int(3) NOT NULL,
  `email` text NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AID`, `username`, `password`, `fname`, `lname`, `sex`, `address`, `tel_no`, `age`, `email`, `profile`) VALUES
('AD101', 'admin', '$2y$10$50FRw7M.ieUY666J1PQhG.22dUCcD4afMLzu6IdNMsas/35lmFxvy', 'bien', 'icles', 0, 'malitam, batangas city', '09167436785', 21, 'bien@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `APID` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `event_by` varchar(11) NOT NULL,
  `category` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `venue` text NOT NULL,
  `reg_fee` int(11) NOT NULL,
  `ref_no` int(11) NOT NULL,
  `ref_img` text NOT NULL,
  `exp_cost` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`APID`, `event_name`, `event_by`, `category`, `start_date`, `end_date`, `venue`, `reg_fee`, `ref_no`, `ref_img`, `exp_cost`, `total_cost`, `status`, `date_created`) VALUES
(2, 'test event', '1', 'Funerals', '2024-11-14 04:57:00', '2024-11-14 06:58:00', 'BPC Chapel', 3000, 1234567890, '../../assets/ref/image_2024-11-10_120721729-removebg-preview-removebg-preview.png', 10000, 0, 0, '2024-11-19 04:01:30.597920');

-- --------------------------------------------------------

--
-- Table structure for table `ar_aptn`
--

CREATE TABLE `ar_aptn` (
  `APID` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `event_by` varchar(11) NOT NULL,
  `category` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `venue` text NOT NULL,
  `reg_fee` int(11) NOT NULL,
  `ref_no` int(11) NOT NULL,
  `ref_img` text NOT NULL,
  `exp_cost` int(11) NOT NULL,
  `total_cost` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `church_events`
--

CREATE TABLE `church_events` (
  `CHID` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `event_by` varchar(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `attendees` int(11) NOT NULL,
  `donation` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `expenses` int(11) NOT NULL,
  `offering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `church_events`
--

INSERT INTO `church_events` (`CHID`, `event_name`, `event_by`, `start_date`, `end_date`, `attendees`, `donation`, `budget`, `expenses`, `offering`) VALUES
(1, 'Sunday Mass', 'SF101', '2024-11-07 05:11:21', '2024-11-07 14:11:21', 82, 500, 2000, 2000, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `church_mem`
--

CREATE TABLE `church_mem` (
  `CMID` varchar(11) NOT NULL,
  `username` varchar(11) NOT NULL,
  `password` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `sex` int(1) NOT NULL,
  `address` text NOT NULL,
  `tel_no` text NOT NULL,
  `age` int(3) NOT NULL,
  `email` text NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `church_mem`
--

INSERT INTO `church_mem` (`CMID`, `username`, `password`, `fname`, `lname`, `sex`, `address`, `tel_no`, `age`, `email`, `profile`) VALUES
('CM101', 'church_mem', '$2y$10$.HXZxRcezS8sJaSz2qqWOez0VqLMF3ja.OP1PXY6uOheGfPLScUgS', 'church', 'member', 0, 'batangas city', '09123456789', 21, 'church_mem@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CID` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `sex` int(1) NOT NULL,
  `address` text NOT NULL,
  `tel_no` text NOT NULL,
  `age` int(3) NOT NULL,
  `email` text NOT NULL,
  `profile` text NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CID`, `username`, `password`, `fname`, `lname`, `sex`, `address`, `tel_no`, `age`, `email`, `profile`, `date_created`) VALUES
(1, 'test', '$2y$10$9Olau.98HIBHrYrhA1I2M.EgSLdcpsUJ7UDum/tHdYCEP7tX0GZTu', 'Tyler', 'The Fatty Liver', 0, 'batangas city', '09123456789', 21, 'test@gmail.com', 'uploads/672fe1be7c1cf-Screenshot 2024-10-02 220619.png', '2024-11-13 16:48:08.880989'),
(2, 'emman', '$2y$10$mPJnKv77wAnMxPhJhHjPFeHlpo4EChCVE4ggLjtXID4vEt5FWuX5S', 'emman', 'flores', 0, 'batangas city', '09912534274', 21, 'emman@gmail.com', '', '2024-11-05 20:38:46.997685');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `SFID` varchar(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `sex` int(1) NOT NULL,
  `address` text NOT NULL,
  `tel_no` text NOT NULL,
  `age` int(3) NOT NULL,
  `email` text NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`SFID`, `username`, `password`, `fname`, `lname`, `sex`, `address`, `tel_no`, `age`, `email`, `profile`) VALUES
('SF101', 'staff', '$2y$10$RjAQKSmyXYtKs.3Ca0BfrueQvbAZsFA0nGtWQ3uEe33fmoYAB7wD2', 'staff', 'a', 0, 'batangas city', '09123456789', 21, 'staff@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `SID` varchar(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `sex` int(1) NOT NULL,
  `address` text NOT NULL,
  `tel_no` text NOT NULL,
  `age` int(3) NOT NULL,
  `email` text NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`SID`, `username`, `password`, `fname`, `lname`, `sex`, `address`, `tel_no`, `age`, `email`, `profile`) VALUES
('S101', 'lrn', '$2y$10$deIcJlWIbMmxVi4PIvb7jOnYHqbjUJqhjD0IaHF5HzTBkqWOAJq/K', 'elijah', 'pascual', 0, 'batangas city', '09123456789', 21, 'student@gmail.com', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`APID`);

--
-- Indexes for table `ar_aptn`
--
ALTER TABLE `ar_aptn`
  ADD PRIMARY KEY (`APID`);

--
-- Indexes for table `church_events`
--
ALTER TABLE `church_events`
  ADD PRIMARY KEY (`CHID`);

--
-- Indexes for table `church_mem`
--
ALTER TABLE `church_mem`
  ADD PRIMARY KEY (`CMID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`SFID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`SID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `APID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ar_aptn`
--
ALTER TABLE `ar_aptn`
  MODIFY `APID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `church_events`
--
ALTER TABLE `church_events`
  MODIFY `CHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
