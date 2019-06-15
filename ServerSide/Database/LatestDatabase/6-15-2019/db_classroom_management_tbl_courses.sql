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
-- Table structure for table `tbl_courses`
--

DROP TABLE IF EXISTS `tbl_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbl_courses` (
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_credit_hours` int(11) NOT NULL,
  `total_students_enrolled` int(11) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_id_UNIQUE` (`course_id`),
  KEY `teacher_id_idx` (`teacher_id`),
  CONSTRAINT `teacher_courses` FOREIGN KEY (`teacher_id`) REFERENCES `tbl_teachers` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_courses`
--

LOCK TABLES `tbl_courses` WRITE;
/*!40000 ALTER TABLE `tbl_courses` DISABLE KEYS */;
INSERT INTO `tbl_courses` VALUES (1001,1001,'Basic Electronics',2,27),(1002,1001,'Functonal English',2,46),(1003,1001,'Pakistan Studies',2,54),(1004,1001,'Islamic Studies',2,43),(1005,1002,'Numerical Computing',3,27),(1006,1002,'Compiler Construction',3,44),(1007,1002,'Marketing and Management',3,55),(1008,1002,'Discrete Structure',3,33),(1009,1002,'Objecct Oriented Programming',3,45),(1010,1002,'Operating System',3,42),(1011,1003,'Software Project Management',3,23),(1012,1003,'Financial Management',3,31);
/*!40000 ALTER TABLE `tbl_courses` ENABLE KEYS */;
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
