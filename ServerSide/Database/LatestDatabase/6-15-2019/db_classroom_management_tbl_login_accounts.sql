CREATE DATABASE  IF NOT EXISTS `db_classroom_management` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_classroom_management`;
-- MySQL dump 10.13  Distrib 8.0.16, for Win64 (x86_64)
--
-- Host: localhost    Database: db_classroom_management
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_login_accounts`
--

DROP TABLE IF EXISTS `tbl_login_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbl_login_accounts` (
  `account_id` int(11) NOT NULL,
  `permission_level` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  UNIQUE KEY `account_id_UNIQUE` (`account_id`),
  KEY `account_id_idx` (`account_id`),
  CONSTRAINT `account_teacher` FOREIGN KEY (`account_id`) REFERENCES `tbl_teachers` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_login_accounts`
--

LOCK TABLES `tbl_login_accounts` WRITE;
/*!40000 ALTER TABLE `tbl_login_accounts` DISABLE KEYS */;
INSERT INTO `tbl_login_accounts` VALUES (1001,'Teacher','Hamdard123'),(1002,'Teacher','Hamdard123'),(1003,'Teacher','Hamdard123'),(1004,'Teacher','Hamdard123'),(1005,'Teacher','Hamdard123'),(1006,'Teacher','Hamdard123'),(1007,'Teacher','Hamdard123'),(1008,'Teacher','Hamdard123'),(1009,'Teacher','Hamdard123'),(1010,'Teacher','Hamdard123'),(1011,'Teacher','Hamdard123'),(1012,'HOD','HamdardHOD123'),(1013,'Teacher','Hamdard123'),(1014,'Teacher','Hamdard123'),(1015,'QMD','HamdardQMD123');
/*!40000 ALTER TABLE `tbl_login_accounts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-15  3:26:41
