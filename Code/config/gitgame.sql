-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2022 at 12:17 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

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
-- Table structure for table `challenge`
--

CREATE TABLE `challenge` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`id`, `name`) VALUES
(1, 'Challenge 1'),
(2, 'Challenge 2'),
(3, 'Challenge 3');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `question` varchar(800) NOT NULL,
  `hint` varchar(800) NOT NULL,
  `solution` varchar(800) NOT NULL,
  `task_number` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `question`, `hint`, `solution`, `task_number`, `challenge_id`) VALUES
(1, 'To check if GIT is installed there is a simple command to do that.', ' You should consider version command for this.', 'git --version', 1, 1),
(4, ' You have to connect to your Github Account, in order to upload changes in your repository. (Use name as your name and your.email.address@mail.com as email address)\r\n', 'There are 2 commands to do that', 'git config --global user.name \"Name\"#git config --global user.email your.email.address@mail.com', 2, 1),
(5, 'Create a new Repository in a new Folder (Folder name: newFolder)\r\n', 'Use the command \'mkdir <foldername>\' to create a new folder\r\nand then INITialize your repository.\r\n', 'mkdir newFolder#git init\r\n', 3, 1),
(6, ' Suppose there is already a file in your current folder. Now check the current status.\r\n', 'Add the 2 words together and type in the command\r\n', 'git status', 4, 1),
(7, ' Add all changes to the repository.\r\n', 'Use a \'.\' (dot) to complete the command\r\n', 'git add .\n', 5, 1),
(8, 'Commit your changes and add a message to it\r\n', 'Use \'-m\' to add a message', 'git commit -m \"<Message>\"', 6, 1),
(9, 'Now push your changes to your repository\r\n', 'There are just 2 words in this command\r\n', 'git push', 7, 1),
(17, 'Create a new branch named \"new_branch\" and then checkout to this branch.', 'This task consists of 2 commands!', 'git branch new_branch#git checkout new_branch', 1, 2),
(18, 'Make a commit on the \"new_branch\" branch with the msg \"git.txt added\" where you create a new file called \"git.txt\"', 'Create the file first, then commit the changes!', 'touch git.txt#git add .#git commit -m \"git.txt added\"', 2, 2),
(19, 'Create a hotfix branch on master, create a \"git.txt\" file and commit it with the message \"git.txt added\".', 'Don\'t forget to go back to master!', 'git checkout master#git branch hotfix#git checkout hotfix#touch git.txt#git add .#git commit -m \"git.txt added\"', 3, 2),
(20, 'Merge the \"new_branch\" branch into master branch!', 'The merging is done from the branch that wants to take in the other branch!', 'git checkout master#git merge new_branch', 4, 2),
(21, 'Is a merge conflict going to happen? (YES or NO)', 'Remember when conflicts occur!', 'NO', 5, 2),
(22, 'Merge \"hotfix\" branch back to master as well now.', 'No hint!', 'git merge hotfix', 6, 2),
(23, 'Is a merge conflict going to happen? (YES or NO)', 'Remember when conflicts occur!', 'YES', 7, 2),
(24, 'How to fix the conflict. Check where the problem lies. Then fix the problem (use VIM as editot) and finish the merging with msg \"merging done\".', '', 'git status#vim git.txt#git add .#git commit -m \"merging done\"', 8, 2);

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
  `Token` varchar(128) DEFAULT NULL,
  `Token2` varchar(128) DEFAULT NULL,
  `Score` INT(11) NOT NULL DEFAULT '0'

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Gender`, `Firstname`, `Lastname`, `Username`, `Email`, `Password`, `Avatar`, `RegisteredAt`, `LastLogin`, `Bio`, `Token`, `Token2`) VALUES
(1, 'female', 'Pavle', 'Tomanovic', 'Paka94', 'tomanovic.pavlee@gmail.com', '216afd3c01b2bb04649fadbe08a6d78fc651f9cace5a3f187aba8fbd2f045cb7', NULL, '2021-10-20 16:31:49', NULL, NULL, NULL, 'Ld4qW131g0KQl3fn'),
(2, 'male', 'Pavlee', 'Tomanovicc', 'Paki94', 'pakibulke@gmail.com', '5b5e03874f78ed61e9ed1e0071dc08e05ef6ca31017e718e464bd12bd4136e74', '71ZCJYuOqIL.png', '2021-11-18 09:08:23', NULL, NULL, 'UNW4bb0W4Xr4hlw2', 'SY3z7P73T1h6oHnb'),
(3, 'male', 'Pavle', 'Tomanovic', 'Pavlee', 'pakibulk1@gmail.com', '5b5e03874f78ed61e9ed1e0071dc08e05ef6ca31017e718e464bd12bd4136e74', NULL, '2021-11-25 13:58:13', NULL, NULL, NULL, 'Y0g2qJ2iYnoFgvqv'),
(4, 'male', 'Admin', 'Admin', 'admin123', 'admin@fmail.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', NULL, '2022-01-12 20:09:48', NULL, NULL, NULL, NULL),
(5, 'male', 'Pavle', 'Tomanovic', 'PavleTomanovic', 'pakibulke1@gmail.com', '5b5e03874f78ed61e9ed1e0071dc08e05ef6ca31017e718e464bd12bd4136e74', NULL, '2022-03-28 15:43:16', NULL, NULL, 'ZIl7uLvHh24BvLds', NULL),
(6, 'male', 'test', 'testi', 'test_user', 'test@gmail.com', '8122cba12b897aa5546baf90b6c82c9f646f976b3555033cbc5e0b72d4f7a5bc', NULL, '2022-05-26 13:48:57', NULL, NULL, '8nRIVSK7tYN5rfi0', NULL),
(7, 'male', 'filani', 'fisteku', 'filaniii', 'filanfistekuu1999@gmail.com', '1cd73874884db6a0f9f21d853d7e9eacdc773c39ee389060f5e96ae0bcb4773a', NULL, '2022-05-26 17:10:03', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_challenge`
--

CREATE TABLE `user_challenge` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `last_done_task` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_challenge`
--

INSERT INTO `user_challenge` (`id`, `user_id`, `challenge_id`, `last_done_task`) VALUES
(1, 2, 1, 0),
(2, 5, 1, 0),
(3, 0, 1, 0),
(4, 0, 1, 0),
(5, 0, 1, 0),
(6, 6, 2, 1),
(7, 7, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user_challenge`
--
ALTER TABLE `user_challenge`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `challenge`
--
ALTER TABLE `challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_challenge`
--
ALTER TABLE `user_challenge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
