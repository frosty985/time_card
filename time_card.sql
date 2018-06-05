-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 05, 2018 at 10:15 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `time_card`
--
CREATE DATABASE IF NOT EXISTS `time_card` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `time_card`;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--
-- Creation: May 26, 2018 at 09:09 AM
-- Last update: May 31, 2018 at 06:56 PM
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `cid` char(32) NOT NULL,
  `cname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pass`
--
-- Creation: May 30, 2018 at 07:44 PM
-- Last update: Jun 05, 2018 at 08:43 PM
--

DROP TABLE IF EXISTS `pass`;
CREATE TABLE `pass` (
  `uid` char(32) NOT NULL,
  `hashd` char(60) NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--
-- Creation: Jun 04, 2018 at 09:39 PM
-- Last update: Jun 05, 2018 at 04:14 PM
--

DROP TABLE IF EXISTS `time`;
CREATE TABLE `time` (
  `tid` char(32) NOT NULL,
  `uid` char(32) NOT NULL,
  `cid` char(32) NOT NULL,
  `sType` set('Shift','Break','Holiday','Bank Holiday') NOT NULL,
  `tdate` date NOT NULL,
  `stime` time DEFAULT NULL,
  `ftime` time DEFAULT NULL,
  `utime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--
-- Creation: Jun 04, 2018 at 08:26 PM
-- Last update: Jun 05, 2018 at 04:15 PM
--

DROP TABLE IF EXISTS `todo`;
CREATE TABLE `todo` (
  `tid` char(32) NOT NULL,
  `uid` char(32) NOT NULL,
  `date` date NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priority` int(2) UNSIGNED ZEROFILL NOT NULL,
  `completed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
-- Creation: May 26, 2018 at 09:05 AM
-- Last update: May 30, 2018 at 07:48 PM
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` char(32) NOT NULL,
  `uname` varchar(40) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `lacdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_comp`
--
-- Creation: Jun 05, 2018 at 09:08 PM
--

DROP TABLE IF EXISTS `user_comp`;
CREATE TABLE `user_comp` (
  `ucid` char(32) NOT NULL,
  `uid` char(32) NOT NULL,
  `cid` char(32) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `edate` date NOT NULL,
  `udate` datetime NOT NULL,
  `ptype` set('week','2week','4week','month') DEFAULT NULL,
  `pdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `pass`
--
ALTER TABLE `pass`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `cid` (`cid`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_comp`
--
ALTER TABLE `user_comp`
  ADD PRIMARY KEY (`ucid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `cid` (`cid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pass`
--
ALTER TABLE `pass`
  ADD CONSTRAINT `pass_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE;

--
-- Constraints for table `time`
--
ALTER TABLE `time`
  ADD CONSTRAINT `time_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `company` (`cid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `time_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE;

--
-- Constraints for table `todo`
--
ALTER TABLE `todo`
  ADD CONSTRAINT `todo_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE;

--
-- Constraints for table `user_comp`
--
ALTER TABLE `user_comp`
  ADD CONSTRAINT `user_comp_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `company` (`cid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comp_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE;
