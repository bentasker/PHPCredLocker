-- MySQL dump 10.13  Distrib 5.1.61, for redhat-linux-gnu (i386)
--
-- Host: localhost    Database: VirCreds
-- ------------------------------------------------------
-- Server version	5.1.61

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
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Audit`
--

LOCK TABLES `Audit` WRITE;
/*!40000 ALTER TABLE `Audit` DISABLE KEYS */;
INSERT INTO `Audit` VALUES (1,'2012-11-24 19:20:27','ben','',11),(2,'2012-11-24 19:42:26','ben','',11),(3,'2012-11-24 19:42:45','ben','',11),(4,'2012-11-24 19:45:11','ben','',11),(5,'2012-11-24 19:46:14','ben','',11),(6,'2012-11-24 19:47:31','ben','',11),(7,'2012-11-24 19:48:21','ben','',11),(8,'2012-11-24 19:49:14','ben','',11),(9,'2012-11-24 19:49:45','ben','',11),(10,'2012-11-24 19:50:32','ben','',11),(11,'2012-11-24 19:52:17','ben','',11),(12,'2012-11-24 20:00:08','ben','',11),(13,'2012-11-24 20:01:37','ben','',11),(14,'2012-11-24 20:01:40','ben','ben',12),(15,'2012-11-24 21:40:50','ben','',11),(16,'2012-11-24 21:56:38','ben','',11),(17,'2012-11-24 22:21:35','ben','',11),(18,'2012-11-26 12:28:43','ben','',11),(19,'2012-11-26 12:28:49','ben','15',3),(20,'2012-11-26 12:33:18','ben','15',5),(21,'2012-11-26 12:33:39','ben','15',5),(22,'2012-11-26 12:34:26','ben','15',5),(23,'2012-11-26 12:34:35','ben','15',5),(24,'2012-11-26 12:38:34','ben','15',5),(25,'2012-11-26 12:53:13','ben','',11),(26,'2012-11-26 12:56:24','ben','15',6),(27,'2012-11-26 12:56:36','ben','16',3),(28,'2012-11-26 12:56:40','ben','16',5),(29,'2012-11-26 12:56:44','ben','1',7),(30,'2012-11-26 12:56:47','ben','16',5),(31,'2012-11-26 12:56:50','ben','1',9),(32,'2012-11-26 12:57:08','ben','16',6),(33,'2012-11-26 12:57:52','ben','16',5),(34,'2012-11-26 12:59:14','ben','16',5),(35,'2012-11-26 12:59:20','ben','17',3),(36,'2012-11-26 12:59:27','ben','18',3),(37,'2012-11-26 13:01:51','ben','17',6),(38,'2012-11-26 13:16:21','ben','',11),(39,'2012-11-26 13:16:28','ben','18',5),(40,'2012-11-26 13:17:23','ben','2',7),(41,'2012-11-26 13:17:26','ben','18',5),(42,'2012-11-26 13:17:29','ben','2',9),(43,'2012-11-26 13:19:23','ben','',11),(44,'2012-11-26 13:45:02','ben','',11),(45,'2012-11-26 13:45:08','ben','18',5),(46,'2012-11-26 13:45:27','ben','4',15),(47,'2012-11-26 13:45:31','ben','18',5),(48,'2012-11-26 13:47:26','ben','5',15),(49,'2012-11-26 13:47:59','ben','6',15),(50,'2012-11-26 13:48:50','ben','18',5),(51,'2012-11-26 13:52:29','ben','18',5),(52,'2012-11-26 13:52:42','ben','18',5),(53,'2012-11-26 13:52:58','ben','18',5),(54,'2012-11-26 13:53:03','ben','18',5),(55,'2012-11-26 13:53:51','ben','18',5),(56,'2012-11-26 13:53:54','ben','18',5),(57,'2012-11-26 17:38:48','ben','',11),(58,'2012-11-26 17:39:06','ben','7',15),(59,'2012-11-26 17:41:48','ben','8',15),(60,'2012-11-26 17:42:37','ben','9',15),(61,'2012-11-26 17:43:24','ben','10',15),(62,'2012-11-26 18:17:22','ben','',11),(63,'2012-11-26 18:17:44','ben','',11),(64,'2012-11-26 18:17:59','ben','',11),(65,'2012-11-26 18:18:02','ben','18',5),(66,'2012-11-26 18:18:07','ben','3',7),(67,'2012-11-26 18:18:54','ben','4',7),(68,'2012-11-26 18:19:14','ben','5',7),(69,'2012-11-26 18:44:46','ben','',11),(70,'2012-11-26 18:46:17','ben','19',3),(71,'2012-11-26 18:52:42','ben','18',5),(72,'2012-11-26 18:52:51','ben','6',7),(73,'2012-11-26 18:53:54','ben','7',7),(74,'2012-11-26 18:54:28','ben','8',7),(75,'2012-11-26 18:54:56','ben','19',5),(76,'2012-11-26 18:55:09','ben','11',15),(77,'2012-11-26 18:55:11','ben','19',5),(78,'2012-11-26 18:55:18','ben','9',7),(79,'2012-11-26 18:57:13','ben','10',7),(80,'2012-11-28 22:24:38','ben','',11),(81,'2012-11-28 22:25:06','ben','2',13),(82,'2012-11-28 22:25:11','ben','3',13),(83,'2012-11-28 22:39:47','ben','',11),(84,'2012-11-28 22:47:22','ben','ben',1),(85,'2012-11-28 22:50:09','ben','ben',1),(86,'2012-11-28 22:50:18','ben','ben',1),(87,'2012-11-28 22:54:09','ben','ben',1),(88,'2012-12-06 00:40:02','ben','',11),(89,'2012-12-06 00:40:11','ben','',11),(90,'2012-12-06 00:42:01','ben','',11),(91,'2012-12-06 00:42:41','ben','',11),(92,'2012-12-06 00:43:27','ben','',11),(93,'2012-12-06 00:45:07','ben','',11),(94,'2012-12-06 00:46:04','ben','',11),(95,'2012-12-06 00:46:37','ben','12',15);
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CredTypes`
--

LOCK TABLES `CredTypes` WRITE;
/*!40000 ALTER TABLE `CredTypes` DISABLE KEYS */;
INSERT INTO `CredTypes` VALUES (12,'R3ySLauELiYEGS6TCbvHeg==');
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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cust`
--

LOCK TABLES `Cust` WRITE;
/*!40000 ALTER TABLE `Cust` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Groups`
--

LOCK TABLES `Groups` WRITE;
/*!40000 ALTER TABLE `Groups` DISABLE KEYS */;
INSERT INTO `Groups` VALUES (2,'4ul7uBMOpM0='),(3,'xJfg/5sExGY=');
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
INSERT INTO `Sessions` VALUES ('1acd3ffb4126b754dff260767cd45626','2012-11-24 19:42:26','ben','2012-11-24 19:57:26','192.168.1.74',''),('bf9a1f8fa0295c4322afea2cfbda483b','2012-11-24 19:42:45','ben','2012-11-24 19:57:45','192.168.1.74',''),('72d52650f9f8e6cc798309dc46069113','2012-11-24 19:45:11','ben','2012-11-24 20:00:11','192.168.1.74',''),('e2301292b9b6a31a60b207fbdfb1d77f','2012-11-24 19:46:14','ben','2012-11-24 20:01:14','192.168.1.74',''),('819fb9c198d4bab4250468ffe88099f1','2012-11-24 19:47:31','ben','2012-11-24 20:02:31','192.168.1.74',''),('2295a656522be1284f5e5def0e01ab69','2012-11-24 19:48:21','ben','2012-11-24 20:03:21','192.168.1.74',''),('77080477501511df1ae1c4d8183cb25f','2012-11-24 19:49:14','ben','2012-11-24 20:04:14','192.168.1.74',''),('fc3f19f71e4bbb82af6c60f9cedb4a28','2012-11-24 19:49:45','ben','2012-11-24 20:04:45','192.168.1.74',''),('66c40bddca9c800ba932c5bdab2a789a','2012-11-24 19:50:32','ben','2012-11-24 20:05:32','192.168.1.74',''),('f137f1b638e010c0f82030cc1acf7d13','2012-11-24 20:00:08','ben','2012-11-24 20:15:08','192.168.1.74',''),('d878272af7ea7ff2b9558caac003afc5','2012-11-24 21:40:50','ben','2012-11-24 21:55:50','192.168.1.74',''),('5822929f79a7c8a8ea286d04cca7c011','2012-11-24 21:56:38','ben','2012-11-24 22:11:38','192.168.1.74',''),('ee9acb4562576183f4f9bd5a7d619ca5','2012-11-24 22:21:35','ben','2012-11-24 22:36:35','192.168.1.74',''),('a469605f4af7dbda09558087364e4857','2012-11-26 12:28:43','ben','2012-11-26 12:43:43','127.0.0.1',''),('b41259bf57a6e4253e86ad915d928491','2012-11-26 12:53:13','ben','2012-11-26 13:08:13','127.0.0.1',''),('125b932cb424a07a6dffdc0e700dcacc','2012-11-26 13:16:21','ben','2012-11-26 13:31:21','192.168.100.6',''),('295d97c3020a5a4c15534c91abf409d0','2012-11-26 13:19:23','ben','2012-11-26 13:34:23','127.0.0.1',''),('36646e6c1b5ff604d2c02ac859197f8e','2012-11-26 13:45:02','ben','2012-11-26 14:00:02','127.0.0.1',''),('8af832c14e2c6f457284de5cdf94bf87','2012-11-26 17:38:48','ben','2012-11-26 17:53:48','127.0.0.1',''),('b162c7526e8e22ad0e221af64260bc77','2012-11-26 18:17:22','ben','2012-11-26 18:32:22','127.0.0.1',''),('c785150aa439cb78da1f652eb924b058','2012-11-26 18:17:44','ben','2012-11-26 18:32:44','127.0.0.1',''),('f37b7e9739c603d5894b6875fdac0857','2012-11-26 18:17:59','ben','2012-11-26 18:32:59','127.0.0.1',''),('9d202bfbfa90378a78dbd64c7aa063d1','2012-11-26 18:44:46','ben','2012-11-26 18:59:46','127.0.0.1',''),('59bf2ac06e12fa7b612a672d8a291c6d','2012-11-28 22:24:38','ben','2012-11-28 22:39:38','192.168.1.74',''),('d415b01d228b68c752484286ec10208f','2012-11-28 22:39:47','ben','2012-11-28 22:54:47','192.168.1.74',''),('32e71761f4d0d7645ac3e37252bba543','2012-12-06 00:40:02','ben','2012-12-06 00:55:02','192.168.1.74','4afa8f9e90756f0f919a124a1dfbba19be004edc'),('bdb2761efe987c71f062d51f948a1e61','2012-12-06 00:40:11','ben','2012-12-06 00:55:11','192.168.1.74','1cc641954099c249e0e4ef0402da3fd0364d95f0'),('1504e3ea18616ef5ca609d36a3dfe443','2012-12-06 00:42:01','ben','2012-12-06 00:57:01','192.168.1.74','5f1cd7c3fb68ae7c679f8c33966610670d32ff1e'),('2617395f90f10267be173b8ea2a6cb85','2012-12-06 00:42:41','ben','2012-12-06 00:57:41','192.168.1.74','667be543b02294b7624119adc3a725473df39885'),('cb2c531cff062ca56ef75e53813d93a8','2012-12-06 00:43:27','ben','2012-12-06 00:58:27','192.168.1.74','54ceb91256e8190e474aa752a6e0650a2df5ba37'),('80bdb308e00114dbc0f666914255ba7a','2012-12-06 00:45:07','ben','2012-12-06 01:00:07','192.168.1.74','12f0de3dc76e067d21ed85125716e02e9f1e69f0'),('506d2ec4987cce1dd839daff2609a6d3','2012-12-06 00:46:04','ben','2012-12-06 01:01:04','192.168.1.74','d321d6f7ccf98b51540ec9d933f20898af3bd71e');
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
INSERT INTO `Users` VALUES ('ben','/Q35YZKh+md3nxVusdWK7bfGxC6WSsj1K7iH2KhMIJdAGq1NY+fJB5FLu2LvNQN1RI1j9fYHSm/x6EoiF3ZO3eMvxc3Eq+hF21AGg6Ku0P0=','B Tasker','-1',0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-06  0:47:18
