-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: VirCreds
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.10

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Audit`
--

DROP TABLE IF EXISTS `Audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `User` varchar(150) DEFAULT NULL,
  `Cust` varchar(150) DEFAULT NULL,
  `Action` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Audit`
--

LOCK TABLES `Audit` WRITE;
/*!40000 ALTER TABLE `Audit` DISABLE KEYS */;
INSERT INTO `Audit` VALUES (1,'2012-12-06 12:43:34','ben','',11),(2,'2012-12-06 12:44:31','ben','',11),(3,'2012-12-06 12:45:50','ben','12',15),(4,'2012-12-06 12:46:02','ben','13',15),(5,'2012-12-06 12:46:21','ben','20',3),(6,'2012-12-06 12:46:27','ben','1',13),(7,'2012-12-06 12:46:36','ben','2',13),(8,'2012-12-06 12:46:54','ben','21',3),(9,'2012-12-06 12:47:06','ben','22',3),(10,'2012-12-06 12:47:17','ben','23',3),(11,'2012-12-06 12:47:21','ben','20',5),(12,'2012-12-06 12:47:34','ben','11',7),(13,'2012-12-06 12:47:47','ben','12',7),(14,'2012-12-06 12:47:57','ben','13',7),(15,'2012-12-06 12:48:00','ben','21',5),(16,'2012-12-06 12:48:09','ben','14',7),(17,'2012-12-06 12:48:12','ben','21',5),(18,'2012-12-06 12:48:15','ben','20',5),(19,'2012-12-06 12:49:48','ben','22',5),(20,'2012-12-06 12:49:52','ben','21',5),(21,'2012-12-06 12:49:54','ben','14',9),(22,'2012-12-06 13:08:30','ben','',11),(23,'2012-12-06 13:08:34','ben','20',5),(24,'2012-12-06 13:08:37','ben','11',9),(25,'2012-12-06 13:08:40','ben','22',5),(26,'2012-12-06 13:09:36','ben','14',10),(27,'2012-12-06 13:11:07','ben','12',9),(28,'2012-12-06 13:11:14','ben','12',10),(29,'2012-12-06 13:12:13','ben','21',5),(30,'2012-12-06 13:12:19','ben','23',6),(31,'2012-12-06 13:12:29','ben','13',9),(32,'2012-12-06 13:12:44','ben','13',16),(33,'2012-12-06 13:13:00','ben','12',16),(34,'2012-12-06 13:13:28','ben','ben',1),(35,'2012-12-06 13:19:39','ben','',12),(36,'2012-12-06 13:20:08','ben','',11),(37,'2012-12-06 13:20:26','ben','',12),(38,'2012-12-06 13:21:30','ben','',11),(39,'2012-12-06 13:21:31','ben','',12),(40,'2012-12-06 13:22:39','ben','',11),(41,'2012-12-06 13:22:49','ben','',12),(42,'2012-12-06 13:23:37','ben','',11),(43,'2012-12-06 13:23:41','ben','',12),(44,'2012-12-07 12:26:12','ben','',11),(45,'2012-12-07 12:28:02','ben','',11),(46,'2012-12-07 12:31:03','ben','',11),(47,'2012-12-07 13:16:50','ben','',11),(48,'2012-12-07 13:16:53','ben','',12);
/*!40000 ALTER TABLE `Audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cred`
--

DROP TABLE IF EXISTS `Cred`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cred` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust` int(11) NOT NULL,
  `Added` datetime NOT NULL,
  `Group` int(11) NOT NULL,
  `Hash` blob,
  `CredType` int(11) NOT NULL,
  `Clicky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Address` blob,
  `UName` blob,
  PRIMARY KEY (`id`),
  KEY `idx_Cred_Group` (`Group`),
  KEY `idx_cred_cust` (`cust`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cred`
--

LOCK TABLES `Cred` WRITE;
/*!40000 ALTER TABLE `Cred` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cred` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CredTypes`
--

DROP TABLE IF EXISTS `CredTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CredTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CredTypes`
--

LOCK TABLES `CredTypes` WRITE;
/*!40000 ALTER TABLE `CredTypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `CredTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cust`
--

DROP TABLE IF EXISTS `Cust`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  `Group` int(11) NOT NULL,
  `ContactName` blob,
  `ContactSurname` blob,
  `Email` blob,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `idx_Customers` (`Name`(255)),
  KEY `idx_Cust_Group` (`Group`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cust`
--

LOCK TABLES `Cust` WRITE;
/*!40000 ALTER TABLE `Cust` DISABLE KEYS */;
INSERT INTO `Cust` VALUES (20,'PgknTAW3pLKCwA1rCGQMjQ==',0,'jrsHGTkiI0fb1uqWliEqlg==','IWk1bMllTJvKTA/BD5pdqw==','k+tQS/8rMs32noh0/XFFZw=='),(21,'l9GtKK8PXaR1nXzpYNo4aQ==',2,'ZPWDxapWmjvUSw+Dl5C4XQ==','IWk1bMllTJvKTA/BD5pdqw==','yDPR6BVnwrIUxM035l7+2A=='),(22,'VYhRm4Z2AL/38n5fbWZ+Hw==',0,'66a3zYW5NkdKpz6rH2u75Q==','IWk1bMllTJvKTA/BD5pdqw==','Tb9J/XGM/KkEjdUwG7josg==');
/*!40000 ALTER TABLE `Cust` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FailedLogins`
--

DROP TABLE IF EXISTS `FailedLogins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FailedLogins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `FailedAttempts` int(11) NOT NULL,
  `LastAttempt` datetime NOT NULL,
  `FailedIP` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_failed_user_ip` (`username`,`FailedIP`),
  KEY `idx_failedips` (`FailedIP`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FailedLogins`
--

LOCK TABLES `FailedLogins` WRITE;
/*!40000 ALTER TABLE `FailedLogins` DISABLE KEYS */;
INSERT INTO `FailedLogins` VALUES (1,'ben',13,'2012-12-07 13:20:45','127.0.0.1');
/*!40000 ALTER TABLE `FailedLogins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Groups`
--

DROP TABLE IF EXISTS `Groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Groups`
--

LOCK TABLES `Groups` WRITE;
/*!40000 ALTER TABLE `Groups` DISABLE KEYS */;
INSERT INTO `Groups` VALUES (1,'EldClOtH11aNN9U+I8hLhw=='),(2,'SlZp53tgPBZqR2+Pndn6SQ==');
/*!40000 ALTER TABLE `Groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sessions`
--

DROP TABLE IF EXISTS `Sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sessions` (
  `SessionID` varchar(150) NOT NULL,
  `Created` datetime NOT NULL,
  `User` varchar(150) NOT NULL,
  `Expires` datetime NOT NULL,
  `ClientIP` varchar(100) NOT NULL,
  `SessKey` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`SessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sessions`
--

LOCK TABLES `Sessions` WRITE;
/*!40000 ALTER TABLE `Sessions` DISABLE KEYS */;
INSERT INTO `Sessions` VALUES ('56ab8fe25136f78ad546a23a33144935','2012-12-06 12:43:34','ben','2012-12-06 12:58:34','127.0.0.1','61188f24396807ba7ca38919a158766de935852e'),('20ca54e05085b8fc8932924edb78c3b3','2012-12-06 12:44:31','ben','2012-12-06 12:59:31','127.0.0.1','683e725c03a87baaad2623231644e944e537acab'),('a6eaf73f8b9eecc95531b4dacf019f38','2012-12-07 12:26:12','ben','2012-12-07 12:41:12','127.0.0.1','5a5b0f9b7d3f8fc84c3cef8fd8efaaa6c70d75ab'),('3db3a028030b62315dcc92d32501e434','2012-12-07 12:28:02','ben','2012-12-07 12:43:02','127.0.0.1','450ddec8dd206c2e2ab1aeeaa90e85e51753b8b7'),('c490589385dfc8d40b7a4008a14c30b6','2012-12-07 12:31:03','ben','2012-12-07 12:46:03','127.0.0.1','14bb99f81147d2705f53a1d75337b2ec3e10d23a');
/*!40000 ALTER TABLE `Sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `username` varchar(150) NOT NULL,
  `pass` blob NOT NULL,
  `Name` varchar(255) NOT NULL,
  `membergroup` longtext,
  `Usergroup` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`Usergroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES ('ben','E1G6Hok4dFobB6XYDhZuAIAOF+jwNcnWnn+VCch0De6UaBWvuG8cKxI70lGm9Wb1+NtHodnbFetsCJhxitChUyzmlrLQ4aqIiY6+NOuuaeo=','B Tasker','0,-1',0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bannedIPs`
--

DROP TABLE IF EXISTS `bannedIPs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bannedIPs` (
  `IP` varchar(255) NOT NULL,
  `Expiry` datetime NOT NULL,
  PRIMARY KEY (`IP`),
  KEY `idx_ban_expiry` (`Expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bannedIPs`
--

LOCK TABLES `bannedIPs` WRITE;
/*!40000 ALTER TABLE `bannedIPs` DISABLE KEYS */;
INSERT INTO `bannedIPs` VALUES ('127.0.0.1','2012-12-08 13:20:45');
/*!40000 ALTER TABLE `bannedIPs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'VirCreds'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-07 13:23:52
