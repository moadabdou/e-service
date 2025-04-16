-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: eservice
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  UNIQUE KEY `id_admin` (`id_admin`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deparetement`
--

DROP TABLE IF EXISTS `deparetement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deparetement` (
  `id_deparetement` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  PRIMARY KEY (`id_deparetement`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deparetement`
--

LOCK TABLES `deparetement` WRITE;
/*!40000 ALTER TABLE `deparetement` DISABLE KEYS */;
INSERT INTO `deparetement` VALUES (1,'Computer Science','Department focused on computer programming and information technology'),(2,'Mathematics','Department dedicated to mathematical sciences and research'),(3,'Physics','Department specializing in physical sciences and experimental research'),(4,'Engineering','Department covering various engineering disciplines'),(5,'Business','Department focused on business administration and management');
/*!40000 ALTER TABLE `deparetement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `title` char(30) NOT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `content` varchar(400) NOT NULL,
  `image_url` text DEFAULT NULL,
  PRIMARY KEY (`id_notification`),
  UNIQUE KEY `title` (`title`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (6,46,'2024-02-01 09:00:00','New Module Assignment','read','You have been assigned to teach Database Management',NULL),(8,46,'2024-02-02 11:15:00','Department Meeting','read','Monthly department meeting scheduled for next week',NULL),(9,46,'2024-02-02 14:00:00','Schedule Update','read','Your teaching schedule has been updated',NULL),(10,46,'2024-02-03 09:45:00','System Maintenance','read','System will be down for maintenance on Sunday',NULL),(11,64,'2025-04-07 17:09:17','Welcome to E-service','read','Please change your temporary password as soon as possible for account security. You can do this by going to your profile settings.',NULL);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professor`
--

DROP TABLE IF EXISTS `professor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `professor` (
  `id_professor` int(11) NOT NULL,
  `max_hours` int(11) NOT NULL CHECK (`max_hours` > 0),
  `min_hours` int(11) NOT NULL CHECK (`min_hours` >= 0),
  `role` enum('normal','chef_deparetement','coordonnateur') NOT NULL,
  `id_deparetement` int(11) NOT NULL,
  UNIQUE KEY `id_professor` (`id_professor`),
  KEY `id_deparetement` (`id_deparetement`),
  CONSTRAINT `professor_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`),
  CONSTRAINT `professor_ibfk_2` FOREIGN KEY (`id_professor`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor`
--

LOCK TABLES `professor` WRITE;
/*!40000 ALTER TABLE `professor` DISABLE KEYS */;
INSERT INTO `professor` VALUES (59,10,1,'normal',2),(64,10,1,'normal',3);
/*!40000 ALTER TABLE `professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` char(30) NOT NULL,
  `lastName` char(30) NOT NULL,
  `CIN` char(8) NOT NULL,
  `email` char(100) NOT NULL,
  `role` enum('professor','vacataire','admin') NOT NULL,
  `password` char(255) NOT NULL,
  `phone` char(10) NOT NULL,
  `address` text NOT NULL,
  `birth_date` date NOT NULL,
  `creation_date` datetime NOT NULL,
  `img` text DEFAULT 'blank-profile.webp',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `CIN` (`CIN`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (46,'ENSAH','ADMIN','R000000','admin@ensah.com','admin','$2y$10$fIXAPsiWA7JkDePaE8Ri1u55onCC2Nq7TGxQc17XEAZSdjSR.rUgO','0512345575','ensah ','2008-03-28','2025-04-03 20:43:26','46_7301907675552309.png'),(59,'Moad','Abdou','RC12345','elabdellaod@etu.uae.ac.ma','professor','$2y$10$qXc4l8GJgWu2PTQQeemJV.LWBpehoJe9dKDLZD26it5QoUtDSaQYe','612345678','testjjsdsbds','2001-03-30','2025-04-04 22:10:29','blank-profile.webp'),(64,'Moad','Abdou','RC12344','elabdellaoui.mod@etu.uae.ac.ma','professor','$2y$10$sb/.6ep7gOrhcR.L8fBSCeu/7PpVkUfw0j6VsZiToH6vNfcw29p1G','612345678','testjjsdsbds','2001-04-06','2025-04-07 17:09:17','64_2706955314457879.png');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-16 19:36:45
