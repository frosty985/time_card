-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 30, 2018 at 05:57 PM
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
-- Last update: May 26, 2018 at 10:44 AM
--

CREATE TABLE `company` (
  `cid` char(32) NOT NULL,
  `cname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `company`:
--

-- --------------------------------------------------------

--
-- Table structure for table `pass`
--
-- Creation: May 29, 2018 at 09:13 PM
-- Last update: May 30, 2018 at 03:48 PM
--

CREATE TABLE `pass` (
  `uid` char(32) NOT NULL,
  `hashd` char(60) NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `pass`:
--   `uid`
--       `user` -> `uid`
--

-- --------------------------------------------------------

--
-- Table structure for table `time`
--
-- Creation: May 27, 2018 at 10:00 AM
-- Last update: May 30, 2018 at 10:29 AM
--

CREATE TABLE `time` (
  `tid` char(32) NOT NULL,
  `uid` char(32) NOT NULL,
  `cid` char(32) NOT NULL,
  `sType` set('Shift','Break') NOT NULL,
  `tdate` date NOT NULL,
  `stime` time NOT NULL,
  `ftime` time NOT NULL,
  `utime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `time`:
--   `cid`
--       `company` -> `cid`
--   `uid`
--       `user` -> `uid`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
-- Creation: May 26, 2018 at 09:05 AM
-- Last update: May 30, 2018 at 10:40 AM
--

CREATE TABLE `user` (
  `uid` char(32) NOT NULL,
  `uname` varchar(40) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `lacdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `user`:
--

-- --------------------------------------------------------

--
-- Table structure for table `user_comp`
--
-- Creation: May 30, 2018 at 04:55 PM
--

CREATE TABLE `user_comp` (
  `ucid` char(32) NOT NULL,
  `uid` char(32) NOT NULL,
  `cid` char(32) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `edate` date NOT NULL,
  `udate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONS FOR TABLE `user_comp`:
--   `cid`
--       `company` -> `cid`
--   `uid`
--       `user` -> `uid`
--

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
-- Constraints for table `user_comp`
--
ALTER TABLE `user_comp`
  ADD CONSTRAINT `user_comp_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `company` (`cid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comp_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE;
