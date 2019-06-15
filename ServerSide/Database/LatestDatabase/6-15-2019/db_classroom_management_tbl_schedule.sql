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
-- Table structure for table `tbl_schedule`
--

DROP TABLE IF EXISTS `tbl_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbl_schedule` (
  `room_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `slot` int(11) NOT NULL,
  `day_of_week` int(11) NOT NULL,
  `class_length` int(11) NOT NULL,
  KEY `course_id_idx` (`course_id`),
  KEY `room_schedule` (`room_id`),
  CONSTRAINT `course_schedule` FOREIGN KEY (`course_id`) REFERENCES `tbl_courses` (`course_id`),
  CONSTRAINT `room_schedule` FOREIGN KEY (`room_id`) REFERENCES `tbl_rooms` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_schedule`
--

LOCK TABLES `tbl_schedule` WRITE;
/*!40000 ALTER TABLE `tbl_schedule` DISABLE KEYS */;
INSERT INTO `tbl_schedule` VALUES (1001,1001,1,1,2),(1001,1002,3,1,2),(1001,1003,5,1,2),(1001,1004,1,2,2),(1001,1005,7,1,1),(1001,1005,3,2,2),(1001,1006,7,3,1),(1001,1006,5,2,2),(1001,1007,7,2,1),(1001,1007,1,3,2),(1001,1008,7,4,1),(1001,1008,3,3,2),(1001,1009,1,5,1),(1001,1009,5,3,2),(1001,1010,2,5,1),(1001,1010,1,4,2),(1001,1011,3,5,1),(1001,1011,3,4,2),(1001,1012,4,5,1),(1001,1012,5,4,2);
/*!40000 ALTER TABLE `tbl_schedule` ENABLE KEYS */;
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
