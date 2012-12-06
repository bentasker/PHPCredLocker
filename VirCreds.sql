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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Audit`
--

LOCK TABLES `Audit` WRITE;
/*!40000 ALTER TABLE `Audit` DISABLE KEYS */;
INSERT INTO `Audit` VALUES (1,'2012-12-06 12:43:34','ben','',11),(2,'2012-12-06 12:44:31','ben','',11),(3,'2012-12-06 12:45:50','ben','12',15),(4,'2012-12-06 12:46:02','ben','13',15),(5,'2012-12-06 12:46:21','ben','20',3),(6,'2012-12-06 12:46:27','ben','1',13),(7,'2012-12-06 12:46:36','ben','2',13),(8,'2012-12-06 12:46:54','ben','21',3),(9,'2012-12-06 12:47:06','ben','22',3),(10,'2012-12-06 12:47:17','ben','23',3),(11,'2012-12-06 12:47:21','ben','20',5),(12,'2012-12-06 12:47:34','ben','11',7),(13,'2012-12-06 12:47:47','ben','12',7),(14,'2012-12-06 12:47:57','ben','13',7),(15,'2012-12-06 12:48:00','ben','21',5),(16,'2012-12-06 12:48:09','ben','14',7),(17,'2012-12-06 12:48:12','ben','21',5),(18,'2012-12-06 12:48:15','ben','20',5),(19,'2012-12-06 12:49:48','ben','22',5),(20,'2012-12-06 12:49:52','ben','21',5),(21,'2012-12-06 12:49:54','ben','14',9);
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
INSERT INTO `Cred` VALUES (11,20,'2012-12-06 12:47:34',1,'wU/usY6l+95Gwj69BrCukQ==',12,0,'',''),(12,20,'2012-12-06 12:47:47',1,'saaV51SanxvYzT335hq74A==',13,0,'',''),(13,20,'2012-12-06 12:47:57',0,'T77/ZjAVmpMuSADA2QLqgg==',12,0,'',''),(14,21,'2012-12-06 12:48:09',1,'6kQEe4hLqsf67KOQOq4Y6Q==',13,0,'','4th9O5MzoR8nIyLSZMIveA==');
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
INSERT INTO `CredTypes` VALUES (12,'KNGHQ6lqF7pEPZSeYuLKKA=='),(13,'jujA8hXmAOYQM9mDVXl/9Q==');
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
INSERT INTO `Cust` VALUES (20,'PgknTAW3pLKCwA1rCGQMjQ==',0,'jrsHGTkiI0fb1uqWliEqlg==','IWk1bMllTJvKTA/BD5pdqw==','k+tQS/8rMs32noh0/XFFZw=='),(21,'l9GtKK8PXaR1nXzpYNo4aQ==',2,'ZPWDxapWmjvUSw+Dl5C4XQ==','IWk1bMllTJvKTA/BD5pdqw==','yDPR6BVnwrIUxM035l7+2A=='),(22,'VYhRm4Z2AL/38n5fbWZ+Hw==',0,'66a3zYW5NkdKpz6rH2u75Q==','IWk1bMllTJvKTA/BD5pdqw==','Tb9J/XGM/KkEjdUwG7josg=='),(23,'+VQ9nSOXJvSLOBJrfL4gOA==',1,'23kcqLZ9UCZgQ530w+tevQ==','IWk1bMllTJvKTA/BD5pdqw==','yDPR6BVnwrIUxM035l7+2A==');
/*!40000 ALTER TABLE `Cust` ENABLE KEYS */;
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
INSERT INTO `Sessions` VALUES ('56ab8fe25136f78ad546a23a33144935','2012-12-06 12:43:34','ben','2012-12-06 12:58:34','127.0.0.1','61188f24396807ba7ca38919a158766de935852e'),('20ca54e05085b8fc8932924edb78c3b3','2012-12-06 12:44:31','ben','2012-12-06 12:59:31','127.0.0.1','683e725c03a87baaad2623231644e944e537acab');
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
INSERT INTO `Users` VALUES ('ben','FtS39ubnVXgtKewoJghSnAUM+nmbe+ldTQgZZ0PNXMQbiEq059WnhTRK3gTl8NDTiOC61PGgLjGFXhT9TDMQUO8inT+eTNw4YeJilry6N+s=','B Tasker','-1',0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
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

-- Dump completed on 2012-12-06 12:54:11
