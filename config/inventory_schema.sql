-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: inventory
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `application_installs`
--

DROP TABLE IF EXISTS `application_installs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_installs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `application_id` int NOT NULL,
  `comp_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2093153 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_installs`
--

LOCK TABLES `application_installs` WRITE;
/*!40000 ALTER TABLE `application_installs` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_installs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `version` varchar(50) DEFAULT NULL,
  `monitoring` varchar(12) DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6162 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkout_request`
--

DROP TABLE IF EXISTS `checkout_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkout_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_name` varchar(50) NOT NULL,
  `employee_email` varchar(100) NOT NULL,
  `check_out_date` timestamp NULL DEFAULT NULL,
  `check_in_date` timestamp NULL DEFAULT NULL,
  `device_type` int NOT NULL,
  `status` varchar(10) DEFAULT 'new',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkout_request`
--

LOCK TABLES `checkout_request` WRITE;
/*!40000 ALTER TABLE `checkout_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `checkout_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkout_reservation`
--

DROP TABLE IF EXISTS `checkout_reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkout_reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `device_id` int NOT NULL,
  `saved_device_location` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkout_reservation`
--

LOCK TABLES `checkout_reservation` WRITE;
/*!40000 ALTER TABLE `checkout_reservation` DISABLE KEYS */;
/*!40000 ALTER TABLE `checkout_reservation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commands`
--

DROP TABLE IF EXISTS `commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `parameters` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commands`
--

LOCK TABLES `commands` WRITE;
/*!40000 ALTER TABLE `commands` DISABLE KEYS */;
INSERT INTO `commands` VALUES (2,'Wake Computer','Computer Name','Wake a specific computer via a WOL packet at a given time. '),(8,'Purge Decommissioned Devices','Years','Removes devices that have been decommissioned when the decommission date is greater than the give number of years'),(4,'Send Emails','','This command should be kept running at all times. It will clear the email queue by sending emails to system administrators.'),(5,'Check Disk Space','Minimum Space Threshold','Check the disk space available on all computers. Any that do not contain the minimum amount of space (in percent) will generate an email to the system administrator. '),(9,'Purge Logs','Years','Automatically Removes logs from the system older than the given numbers of years'),(7,'Remove Old Applications','','Removes Applications that are no longer installed on any device from the database'),(10,'Lifecycle Update Check','','checks configured Lifecycles to see if any applications need to be checked for an update'),(11,'Purge Checkout Requests','','Removes inactive checkout requests (approved or unapproved) where the checkout window has expired'),(12,'License Renewal Reminders','','Send email reminders for licenses close to expiration based on the Renewal Reminder time set prior to expiration.');
/*!40000 ALTER TABLE `commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `computer`
--

DROP TABLE IF EXISTS `computer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `computer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `DeviceType` int DEFAULT NULL,
  `EnableMonitoring` varchar(6) NOT NULL DEFAULT 'false',
  `ComputerName` varchar(100) NOT NULL,
  `SerialNumber` varchar(100) NOT NULL DEFAULT '',
  `AssetId` bigint NOT NULL DEFAULT '0',
  `CurrentUser` varchar(100) NOT NULL DEFAULT '',
  `ComputerLocation` varchar(100) NOT NULL DEFAULT '',
  `Manufacturer` varchar(100) DEFAULT NULL,
  `Model` varchar(100) NOT NULL DEFAULT '',
  `OS` varchar(100) NOT NULL DEFAULT '',
  `Memory` bigint NOT NULL DEFAULT '0',
  `MemoryFree` double NOT NULL DEFAULT '0',
  `CPU` varchar(100) NOT NULL DEFAULT '',
  `NumberOfMonitors` int NOT NULL DEFAULT '0',
  `IPaddress` varchar(100) NOT NULL DEFAULT '',
  `IPv6address` varchar(100) DEFAULT NULL,
  `MACaddress` varchar(100) NOT NULL DEFAULT '',
  `DiskSpace` bigint NOT NULL DEFAULT '0',
  `DiskSpaceFree` bigint NOT NULL DEFAULT '0',
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LastBooted` timestamp NOT NULL DEFAULT '1980-01-01 00:00:00',
  `SupplicantUsername` varchar(100) DEFAULT NULL,
  `SupplicantPassword` varchar(100) DEFAULT NULL,
  `ApplicationUpdates` int NOT NULL DEFAULT '0',
  `WipedHD` varchar(10) NOT NULL DEFAULT '',
  `Recycled` varchar(10) NOT NULL DEFAULT '',
  `RedeployedAs` varchar(255) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `WindowsIndex` double(10,2) NOT NULL DEFAULT '0.00',
  `IsAlive` varchar(6) NOT NULL DEFAULT 'true',
  `CanCheckout` varchar(6) DEFAULT 'false',
  `IsCheckedOut` varchar(6) DEFAULT 'false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ComputerName` (`ComputerName`)
) ENGINE=MyISAM AUTO_INCREMENT=403 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `computer`
--

LOCK TABLES `computer` WRITE;
/*!40000 ALTER TABLE `computer` DISABLE KEYS */;
/*!40000 ALTER TABLE `computer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `computer_license`
--

DROP TABLE IF EXISTS `computer_license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `computer_license` (
  `id` int NOT NULL AUTO_INCREMENT,
  `license_id` int DEFAULT NULL,
  `device_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `computer_license`
--

LOCK TABLES `computer_license` WRITE;
/*!40000 ALTER TABLE `computer_license` DISABLE KEYS */;
/*!40000 ALTER TABLE `computer_license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `computer_logins`
--

DROP TABLE IF EXISTS `computer_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `computer_logins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comp_id` int NOT NULL,
  `Username` varchar(30) NOT NULL,
  `LoginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51632 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `computer_logins`
--

LOCK TABLES `computer_logins` WRITE;
/*!40000 ALTER TABLE `computer_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `computer_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `decommissioned`
--

DROP TABLE IF EXISTS `decommissioned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `decommissioned` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ComputerName` varchar(100) NOT NULL,
  `SerialNumber` varchar(100) NOT NULL,
  `AssetId` int NOT NULL,
  `CurrentUser` varchar(100) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `Manufacturer` varchar(100) DEFAULT NULL,
  `Model` varchar(100) NOT NULL,
  `CPU` varchar(100) NOT NULL,
  `NumberOfMonitors` int NOT NULL,
  `IPaddress` varchar(100) NOT NULL,
  `MACaddress` varchar(100) NOT NULL,
  `OS` varchar(100) NOT NULL,
  `Memory` bigint NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `WipedHD` varchar(10) NOT NULL,
  `Recycled` varchar(10) NOT NULL,
  `RedeployedAs` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `device_attributes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `decommissioned`
--

LOCK TABLES `decommissioned` WRITE;
/*!40000 ALTER TABLE `decommissioned` DISABLE KEYS */;
/*!40000 ALTER TABLE `decommissioned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_types`
--

DROP TABLE IF EXISTS `device_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `attributes` text,
  `check_running` varchar(10) DEFAULT NULL,
  `allow_decom` varchar(10) DEFAULT NULL,
  `exclude_ad_sync` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_types`
--

LOCK TABLES `device_types` WRITE;
/*!40000 ALTER TABLE `device_types` DISABLE KEYS */;
INSERT INTO `device_types` VALUES (22,'Computer','desktop-classic','CurrentUser,SerialNumber,Manufacturer,Model,OS,CPU,Memory,NumberOfMonitors,DriveSpace,IPaddress,IPv6address,MACaddress','true','true','false');
/*!40000 ALTER TABLE `device_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disk`
--

DROP TABLE IF EXISTS `disk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comp_id` int NOT NULL,
  `label` varchar(15) NOT NULL,
  `total_space` bigint NOT NULL,
  `space_free` bigint NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'Local',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=528 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disk`
--

LOCK TABLES `disk` WRITE;
/*!40000 ALTER TABLE `disk` DISABLE KEYS */;
/*!40000 ALTER TABLE `disk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(45) NOT NULL,
  `message` text,
  `recipient` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=336 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_queue`
--

LOCK TABLES `email_queue` WRITE;
/*!40000 ALTER TABLE `email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `license_keys`
--

DROP TABLE IF EXISTS `license_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `license_keys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `license_id` int DEFAULT NULL,
  `Quantity` int DEFAULT '1',
  `Keycode` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `license_keys`
--

LOCK TABLES `license_keys` WRITE;
/*!40000 ALTER TABLE `license_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `license_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `LicenseName` varchar(100) NOT NULL,
  `Vendor` varchar(100) DEFAULT NULL,
  `ExpirationDate` timestamp NULL DEFAULT NULL,
  `StartReminder` int DEFAULT '1',
  `Notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licenses`
--

LOCK TABLES `licenses` WRITE;
/*!40000 ALTER TABLE `licenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `licenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lifecycles`
--

DROP TABLE IF EXISTS `lifecycles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lifecycles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `application_id` int NOT NULL,
  `update_frequency` varchar(15) DEFAULT NULL,
  `last_check` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lifecycles`
--

LOCK TABLES `lifecycles` WRITE;
/*!40000 ALTER TABLE `lifecycles` DISABLE KEYS */;
/*!40000 ALTER TABLE `lifecycles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `location` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(255) NOT NULL,
  `auto_regex` text,
  `is_default` varchar(6) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (9,'Default Location','','true');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `DATED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LOGGER` varchar(200) NOT NULL,
  `LEVEL` varchar(10) NOT NULL,
  `USER` varchar(200) DEFAULT NULL,
  `MESSAGE` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2173444 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operating_systems`
--

DROP TABLE IF EXISTS `operating_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operating_systems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `eol_date` date DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operating_systems`
--

LOCK TABLES `operating_systems` WRITE;
/*!40000 ALTER TABLE `operating_systems` DISABLE KEYS */;
/*!40000 ALTER TABLE `operating_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `comp_id` int NOT NULL,
  `program` text NOT NULL,
  `version` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `comp_id` (`comp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7968423 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restricted_programs`
--

DROP TABLE IF EXISTS `restricted_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restricted_programs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restricted_programs`
--

LOCK TABLES `restricted_programs` WRITE;
/*!40000 ALTER TABLE `restricted_programs` DISABLE KEYS */;
/*!40000 ALTER TABLE `restricted_programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `schedule` varchar(15) NOT NULL,
  `command_id` int NOT NULL,
  `parameters` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comp_id` int NOT NULL,
  `name` text,
  `startmode` varchar(20) NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16897304 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'smtp_server','yourmailhost.com'),(2,'smtp_user',''),(3,'smtp_pass',''),(4,'smtp_auth','false'),(5,'outgoing_email','inventory@example.com'),(10,'computer_ignore_list',''),(13,'ldap_host',''),(14,'ldap_port','389'),(15,'ldap_basedn',''),(16,'ldap_user',''),(17,'ldap_password',''),(18,'show_computer_commands','true'),(19,'domain_username',''),(20,'domain_password',''),(21,'shutdown_message',''),(23,'computer_auto_add','true'),(24,'api_auth_key','change_me'),(25,'ldap_computers_basedn',''),(30,'display_attributes',''),(31,'search_domain',''),(99,'auth_type','local'),(101,'home_attributes','CurrentUser,SerialNumber,Model,OS'),(102,'enable_device_checkout','false'),(103,'device_checkout_location','9');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `gravatar` varchar(60) DEFAULT '',
  `send_email` varchar(10) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (12,'Admin','admin','1a1dc91c907325c69271ddf0c944bc72','youremail@yourdomain.com','','false');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-02 14:43:01
