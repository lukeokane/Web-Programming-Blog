-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2017 at 10:33 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lukeokan_lukeokane`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `userName` varchar(30) NOT NULL,
  `ID` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` varchar(4000) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(8) NOT NULL DEFAULT '0',
  `likes` int(8) NOT NULL DEFAULT '0',
  `dislikes` int(8) NOT NULL DEFAULT '0',
  `tags` varchar(256) NOT NULL,
  `imageURL` varchar(255) NOT NULL,
  `allowComments` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `userName` varchar(30) NOT NULL,
  `ID` int(6) NOT NULL,
  `blogID` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int(8) NOT NULL,
  `dislikes` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_votes`
--

CREATE TABLE `blog_votes` (
  `userName` varchar(20) NOT NULL,
  `blogID` int(11) NOT NULL,
  `vote` enum('LIKE','DISLIKE') NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comment_votes`
--

CREATE TABLE `comment_votes` (
  `userName` varchar(20) NOT NULL,
  `commentID` int(6) NOT NULL,
  `vote` enum('LIKE','DISLIKE') NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usersweb`
--

CREATE TABLE `usersweb` (
  `userName` varchar(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(300) NOT NULL,
  `joinDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `postCount` int(11) NOT NULL DEFAULT '0',
  `commentCount` int(11) NOT NULL DEFAULT '0',
  `picURL` varchar(255) NOT NULL DEFAULT 'images/default-profile-picture.png',
  `userType` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `blog_id` (`ID`),
  ADD KEY `username` (`userName`),
  ADD KEY `blog_id_2` (`ID`),
  ADD KEY `blog_id_3` (`ID`),
  ADD KEY `blog_id_4` (`ID`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`userName`,`date`),
  ADD UNIQUE KEY `blog_id_2` (`ID`),
  ADD KEY `username` (`userName`),
  ADD KEY `blog_id_3` (`ID`),
  ADD KEY `blog_id` (`ID`),
  ADD KEY `blogID` (`blogID`);

--
-- Indexes for table `blog_votes`
--
ALTER TABLE `blog_votes`
  ADD PRIMARY KEY (`timestamp`,`blogID`),
  ADD KEY `blog_id` (`blogID`),
  ADD KEY `blog_id_2` (`blogID`);

--
-- Indexes for table `comment_votes`
--
ALTER TABLE `comment_votes`
  ADD PRIMARY KEY (`timestamp`,`commentID`),
  ADD KEY `commentID` (`commentID`);

--
-- Indexes for table `usersweb`
--
ALTER TABLE `usersweb`
  ADD PRIMARY KEY (`userName`),
  ADD KEY `username` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `usersweb` (`userName`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_3` FOREIGN KEY (`blogID`) REFERENCES `blogs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blog_votes`
--
ALTER TABLE `blog_votes`
  ADD CONSTRAINT `blog_votes_ibfk_1` FOREIGN KEY (`blogID`) REFERENCES `blogs` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `comment_votes`
--
ALTER TABLE `comment_votes`
  ADD CONSTRAINT `comment_votes_ibfk_1` FOREIGN KEY (`commentID`) REFERENCES `blog_comments` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
