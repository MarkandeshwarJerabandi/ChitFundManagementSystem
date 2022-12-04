-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2020 at 08:54 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skg`
--
CREATE DATABASE IF NOT EXISTS `skg` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `skg`;

-- --------------------------------------------------------

--
-- Table structure for table `bc_bidding_details`
--

DROP TABLE IF EXISTS `bc_bidding_details`;
CREATE TABLE IF NOT EXISTS `bc_bidding_details` (
  `bidid` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `bcdate` date NOT NULL,
  `biddate` date NOT NULL,
  `bidbymember` int(11) NOT NULL,
  `bidamount` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `companybal` int(11) NOT NULL,
  `paymentmade` int(11) NOT NULL,
  `amtadjustedtopayment` int(11) NOT NULL,
  `netpayment` int(11) NOT NULL,
  `bidtype` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` datetime NOT NULL,
  PRIMARY KEY (`bidid`),
  KEY `bcid` (`bcid`,`bidbymember`),
  KEY `bidbymember` (`bidbymember`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bc_bidding_payment_details`
--

DROP TABLE IF EXISTS `bc_bidding_payment_details`;
CREATE TABLE IF NOT EXISTS `bc_bidding_payment_details` (
  `bc_bid_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `memid` int(11) NOT NULL,
  `bcdate` date NOT NULL,
  `bcpaymentdate` date NOT NULL,
  `actual_payment` bigint(20) NOT NULL,
  `balance_amount` bigint(20) NOT NULL,
  `lastmodifiedon` datetime NOT NULL,
  PRIMARY KEY (`bc_bid_payment_id`),
  KEY `bcid` (`bcid`),
  KEY `memid` (`memid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bc_details`
--

DROP TABLE IF EXISTS `bc_details`;
CREATE TABLE IF NOT EXISTS `bc_details` (
  `bcid` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `startdate` date NOT NULL,
  `bcmembers` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `totalbcamount` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  `months_skipped` int(11) NOT NULL DEFAULT '0',
  `flag` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bcid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bc_member_mapping`
--

DROP TABLE IF EXISTS `bc_member_mapping`;
CREATE TABLE IF NOT EXISTS `bc_member_mapping` (
  `mapid` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `memid` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  PRIMARY KEY (`mapid`),
  KEY `bcid` (`bcid`),
  KEY `memid` (`memid`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members_details`
--

DROP TABLE IF EXISTS `members_details`;
CREATE TABLE IF NOT EXISTS `members_details` (
  `memid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) DEFAULT NULL,
  `lname` varchar(20) NOT NULL,
  `radd` varchar(100) NOT NULL,
  `oadd` varchar(100) NOT NULL,
  `mobile` bigint(11) NOT NULL,
  `landline` bigint(12) DEFAULT NULL,
  `emailid` varchar(50) DEFAULT NULL,
  `altmobile` bigint(11) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  PRIMARY KEY (`memid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members_payment_details`
--

DROP TABLE IF EXISTS `members_payment_details`;
CREATE TABLE IF NOT EXISTS `members_payment_details` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `memid` int(11) NOT NULL,
  `amountpaid` int(11) NOT NULL,
  `paiddate` date NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  `cbname` varchar(30) DEFAULT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `bcid` (`bcid`,`memid`),
  KEY `memid` (`memid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members_payment_details1`
--

DROP TABLE IF EXISTS `members_payment_details1`;
CREATE TABLE IF NOT EXISTS `members_payment_details1` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `memid` int(11) NOT NULL,
  `amountpaid` int(11) NOT NULL,
  `paiddate` date NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  `cbname` varchar(30) DEFAULT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `bcid` (`bcid`,`memid`),
  KEY `memid` (`memid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members_payment_details_copy`
--

DROP TABLE IF EXISTS `members_payment_details_copy`;
CREATE TABLE IF NOT EXISTS `members_payment_details_copy` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `bcid` int(11) NOT NULL,
  `memid` int(11) NOT NULL,
  `dateofbc` date NOT NULL,
  `amountpaid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `paiddate` date NOT NULL,
  `user` varchar(20) NOT NULL,
  `lastmoddate` date NOT NULL,
  `cbname` varchar(30) DEFAULT NULL,
  `remarks` varchar(200) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `bcid` (`bcid`,`memid`),
  KEY `memid` (`memid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `mobile` bigint(11) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bc_bidding_details`
--
ALTER TABLE `bc_bidding_details`
  ADD CONSTRAINT `bc_bidding_details_ibfk_1` FOREIGN KEY (`bcid`) REFERENCES `bc_details` (`bcid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bc_bidding_details_ibfk_2` FOREIGN KEY (`bidbymember`) REFERENCES `members_details` (`memid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bc_member_mapping`
--
ALTER TABLE `bc_member_mapping`
  ADD CONSTRAINT `bc_member_mapping_ibfk_1` FOREIGN KEY (`bcid`) REFERENCES `bc_details` (`bcid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bc_member_mapping_ibfk_2` FOREIGN KEY (`memid`) REFERENCES `members_details` (`memid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_payment_details`
--
ALTER TABLE `members_payment_details`
  ADD CONSTRAINT `members_payment_details_ibfk_1` FOREIGN KEY (`bcid`) REFERENCES `bc_details` (`bcid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_payment_details_ibfk_2` FOREIGN KEY (`memid`) REFERENCES `members_details` (`memid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
