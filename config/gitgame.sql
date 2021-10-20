-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 20, 2021 at 11:08 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gitgame`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` bigint(20) NOT NULL,
  `Gender` enum('male','female','none','') DEFAULT NULL,
  `Firstname` varchar(50) DEFAULT NULL,
  `Lastname` varchar(50) DEFAULT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Avatar` varchar(50) DEFAULT NULL,
  `RegisteredAt` datetime NOT NULL DEFAULT current_timestamp(),
  `LastLogin` datetime DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `Token` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Gender`, `Firstname`, `Lastname`, `Username`, `Email`, `Password`, `Avatar`, `RegisteredAt`, `LastLogin`, `Bio`, `Token`) VALUES
(1, 'male', 'Pavle', 'Tomanovic', 'Paka94', 'tomanovic.pavlee@gmail.com', '216afd3c01b2bb04649fadbe08a6d78fc651f9cace5a3f187aba8fbd2f045cb7', NULL, '2021-10-20 16:31:49', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
