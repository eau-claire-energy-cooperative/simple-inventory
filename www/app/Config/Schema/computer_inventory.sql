-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2013 at 09:20 AM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `computer_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `computer`
--

CREATE TABLE IF NOT EXISTS `computer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ComputerName` varchar(100) NOT NULL,
  `SerialNumber` varchar(100) NOT NULL,
  `AssetId` bigint(255) NOT NULL,
  `CurrentUser` varchar(100) NOT NULL,
  `ComputerLocation` varchar(100) NOT NULL,
  `Model` varchar(100) NOT NULL,
  `OS` varchar(100) NOT NULL,
  `Memory` bigint(15) NOT NULL,
  `MemoryFree` double NOT NULL,
  `CPU` varchar(100) NOT NULL,
  `NumberOfMonitors` int(11) NOT NULL,
  `IPaddress` varchar(100) NOT NULL,
  `MACaddress` varchar(100) NOT NULL,
  `DiskSpace` bigint(255) NOT NULL,
  `DiskSpaceFree` bigint(20) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LastBooted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `WipedHD` varchar(10) NOT NULL,
  `Recycled` varchar(10) NOT NULL,
  `RedeployedAs` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `WindowsIndex` double(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=119 ;

-- --------------------------------------------------------

--
-- Table structure for table `decommissioned`
--

CREATE TABLE IF NOT EXISTS `decommissioned` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ComputerName` varchar(100) NOT NULL,
  `SerialNumber` varchar(100) NOT NULL,
  `AssetId` int(255) NOT NULL,
  `CurrentUser` varchar(100) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `Model` varchar(100) NOT NULL,
  `CPU` varchar(100) NOT NULL,
  `NumberOfMonitors` int(11) NOT NULL,
  `IPaddress` varchar(100) NOT NULL,
  `MACaddress` varchar(100) NOT NULL,
  `DiskSpace` bigint(255) NOT NULL,
  `OS` varchar(100) NOT NULL,
  `Memory` bigint(255) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `WipedHD` varchar(10) NOT NULL,
  `Recycled` varchar(10) NOT NULL,
  `RedeployedAs` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE IF NOT EXISTS `email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(45) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(255) NOT NULL,
  `is_default` varchar(6) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------
--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `location`, `is_default`) VALUES
(1, 'IT', 'false'),
(2, 'Human Resources', 'false'),
(3, 'Office', 'true'),
(4, 'Truck', 'false'),
(8, 'Administration', 'false'),
(13, 'Operations', 'false'),
(15, 'Board Room', 'false');


--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DATED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LOGGER` varchar(200) NOT NULL,
  `LEVEL` varchar(10) NOT NULL,
  `MESSAGE` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107824 ;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE IF NOT EXISTS `programs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) NOT NULL,
  `program` text NOT NULL,
  `version` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `comp_id` (`comp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=613066 ;

-- --------------------------------------------------------

--
-- Table structure for table `restricted_programs`
--

CREATE TABLE IF NOT EXISTS `restricted_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE IF NOT EXISTS `schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule` varchar(15) NOT NULL,
  `command_id` int(11) NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_id` int(11) NOT NULL,
  `name` varchar(75) NOT NULL,
  `startmode` varchar(20) NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1126761 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;


--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'smtp_server', ''),
(2, 'smtp_user', ''),
(3, 'smtp_pass', ''),
(4, 'smtp_auth', 'true'),
(5, 'outgoing_email', 'administrator@domain.com'),
(10, 'computer_ignore_list', 'thor'),
(11, 'auth_type', 'local'),
(13, 'ldap_host', ''),
(14, 'ldap_port', '389'),
(15, 'ldap_basedn', ''),
(16, 'ldap_user', ''),
(17, 'ldap_password', ''),
(18, 'show_computer_commands', 'true'),
(19, 'domain_username', 'administrator'),
(20, 'domain_password', 'password'),
(21, 'shutdown_message', 'The Administrator has initiated a shutdown of your PC'),
(23, 'computer_auto_add', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `send_email` varchar(10) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`) VALUES
(2, 'Temp', 'test', '1a1dc91c907325c69271ddf0c944bc72', 'test@domain.com');


