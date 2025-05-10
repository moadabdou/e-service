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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `id_activite` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `create_time` datetime NOT NULL COMMENT 'Create Time',
  `content` text NOT NULL,
  `icon` varchar(64) NOT NULL,
  PRIMARY KEY (`id_activite`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'2025-05-08 17:36:22','User logged in to the system','fa-sign-in-alt'),(2,'2025-05-08 17:36:22','New document created','fa-file-alt'),(3,'2025-05-08 17:36:22','Payment processed successfully','fa-credit-card'),(4,'2025-05-08 17:36:22','Profile updated','fa-user-edit'),(5,'2025-05-08 17:36:22','File uploaded to cloud storage','fa-cloud-upload-alt'),(6,'2025-05-08 17:36:22','Message sent to support team','fa-envelope'),(7,'2025-05-08 17:36:22','Report generated','fa-chart-bar'),(8,'2025-05-08 17:36:22','Settings updated','fa-cog'),(9,'2025-05-08 17:36:22','Backup completed','fa-database'),(10,'2025-05-08 17:36:22','Task completed','fa-check-circle'),(11,'2025-05-08 18:15:45','Le chef de département Computer Science a été supprimé par l\'administrateur .','fa-user-xmark'),(12,'2025-05-08 18:16:09','Le professeur hassan hassan a été nommé chef de département Computer Science par l\'administrateur .','fa-user-check'),(13,'2025-05-08 18:47:08','Un nouveau vacataire Moad Abdou a été ajouté par le coordonnateur  ','fa-user-plus'),(14,'2025-05-08 18:54:06','Un nouveau vacataire Moad Abdou a été ajouté par le coordonnateur AYOUB Coordonnateur','fa-user-plus'),(15,'2025-05-08 18:55:37','Un nouveau vacataire Moad Abdou a été ajouté par le coordonnateur AYOUB Coordonnateur','fa-user-plus');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `affectation_professor`
--

DROP TABLE IF EXISTS `affectation_professor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affectation_professor` (
  `to_professor` int(11) NOT NULL,
  `by_chef_deparetement` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `annee` year(4) NOT NULL,
  UNIQUE KEY `affectation_professor_u_4` (`id_module`,`annee`),
  KEY `to_professor` (`to_professor`),
  KEY `by_chef_deparetement` (`by_chef_deparetement`),
  KEY `id_module` (`id_module`),
  CONSTRAINT `affectation_professor_ibfk_1` FOREIGN KEY (`to_professor`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `affectation_professor_ibfk_2` FOREIGN KEY (`by_chef_deparetement`) REFERENCES `chef_deparetement` (`id_chef_deparetement`),
  CONSTRAINT `affectation_professor_ibfk_3` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affectation_professor`
--

LOCK TABLES `affectation_professor` WRITE;
/*!40000 ALTER TABLE `affectation_professor` DISABLE KEYS */;
INSERT INTO `affectation_professor` VALUES (3,2,1,2025),(3,2,3,2025);
/*!40000 ALTER TABLE `affectation_professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affectation_vacataire`
--

DROP TABLE IF EXISTS `affectation_vacataire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affectation_vacataire` (
  `to_vacataire` int(11) NOT NULL,
  `by_coordonnateur` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `annee` year(4) NOT NULL,
  KEY `to_vacataire` (`to_vacataire`),
  KEY `by_coordonnateur` (`by_coordonnateur`),
  KEY `id_module` (`id_module`),
  CONSTRAINT `affectation_vacataire_ibfk_1` FOREIGN KEY (`to_vacataire`) REFERENCES `vacataire` (`id_vacataire`),
  CONSTRAINT `affectation_vacataire_ibfk_2` FOREIGN KEY (`by_coordonnateur`) REFERENCES `coordonnateur` (`id_coordonnateur`),
  CONSTRAINT `affectation_vacataire_ibfk_3` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affectation_vacataire`
--

LOCK TABLES `affectation_vacataire` WRITE;
/*!40000 ALTER TABLE `affectation_vacataire` DISABLE KEYS */;
/*!40000 ALTER TABLE `affectation_vacataire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announces`
--

DROP TABLE IF EXISTS `announces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announces` (
  `id_announce` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `create_time` datetime NOT NULL COMMENT 'Create Time',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `id_admin` int(11) NOT NULL,
  PRIMARY KEY (`id_announce`),
  CONSTRAINT `announces_ibfk_1` FOREIGN KEY (`id_announce`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announces`
--

LOCK TABLES `announces` WRITE;
/*!40000 ALTER TABLE `announces` DISABLE KEYS */;
INSERT INTO `announces` VALUES (1,'2025-05-08 14:23:30','test announce','dbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcsczds  shdasjfbhsdbckabshcs',1);
/*!40000 ALTER TABLE `announces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chef_deparetement`
--

DROP TABLE IF EXISTS `chef_deparetement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chef_deparetement` (
  `id_chef_deparetement` int(11) NOT NULL,
  UNIQUE KEY `id_chef_deparetement` (`id_chef_deparetement`),
  CONSTRAINT `chef_deparetement_ibfk_1` FOREIGN KEY (`id_chef_deparetement`) REFERENCES `professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chef_deparetement`
--

LOCK TABLES `chef_deparetement` WRITE;
/*!40000 ALTER TABLE `chef_deparetement` DISABLE KEYS */;
INSERT INTO `chef_deparetement` VALUES (2);
/*!40000 ALTER TABLE `chef_deparetement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `choix_module`
--

DROP TABLE IF EXISTS `choix_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choix_module` (
  `by_professor` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `date_creation` date NOT NULL,
  `date_reponce` date DEFAULT NULL,
  `status` enum('validated','declined','in progress') NOT NULL,
  KEY `by_professor` (`by_professor`),
  KEY `id_module` (`id_module`),
  CONSTRAINT `choix_module_ibfk_1` FOREIGN KEY (`by_professor`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `choix_module_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choix_module`
--

LOCK TABLES `choix_module` WRITE;
/*!40000 ALTER TABLE `choix_module` DISABLE KEYS */;
INSERT INTO `choix_module` VALUES (3,1,'2025-04-29','2025-04-29','validated'),(3,2,'2025-04-29','2025-04-29','declined'),(3,3,'2025-04-29','2025-04-29','validated'),(2,2,'2025-05-06',NULL,'in progress');
/*!40000 ALTER TABLE `choix_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordonnateur`
--

DROP TABLE IF EXISTS `coordonnateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coordonnateur` (
  `id_coordonnateur` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  UNIQUE KEY `id_coordonnateur` (`id_coordonnateur`),
  KEY `id_filiere` (`id_filiere`),
  CONSTRAINT `coordonnateur_ibfk_1` FOREIGN KEY (`id_coordonnateur`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `coordonnateur_ibfk_2` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordonnateur`
--

LOCK TABLES `coordonnateur` WRITE;
/*!40000 ALTER TABLE `coordonnateur` DISABLE KEYS */;
INSERT INTO `coordonnateur` VALUES (4,1),(3,3);
/*!40000 ALTER TABLE `coordonnateur` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deparetement`
--

LOCK TABLES `deparetement` WRITE;
/*!40000 ALTER TABLE `deparetement` DISABLE KEYS */;
INSERT INTO `deparetement` VALUES (1,'Computer Science','Department focused on computer programming and information technology new data'),(2,'Mathematics','Department dedicated to mathematical sciences and research'),(3,'Physics','Department specializing in physical sciences and experimental research'),(4,'Engineering','Department covering various engineering disciplines'),(5,'Business','Department focused on business administration and management');
/*!40000 ALTER TABLE `deparetement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filiere` (
  `id_filiere` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  `id_deparetement` int(11) NOT NULL,
  PRIMARY KEY (`id_filiere`),
  UNIQUE KEY `title` (`title`),
  KEY `id_deparetement` (`id_deparetement`),
  CONSTRAINT `filiere_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filiere`
--

LOCK TABLES `filiere` WRITE;
/*!40000 ALTER TABLE `filiere` DISABLE KEYS */;
INSERT INTO `filiere` VALUES (1,'Computer Science','Likely a strong theoretical focus within the "Ingénierie des  system mes d\'Information et de Télécommunications" program, covering algorithms and data structures. test test',1),(2,'Digital Transformation and AI','Integrated into "Ingenierie des Systèmes d\'Information et de Télécommunications" and possibly "Génie Industriel," involving technology to reshape businesses and utilizing AI.',1),(3,'Data Engineering','A focus within "Ingenierie des Systèmes d\'Information et de Télécommunications" and potentially "Génie Industriel," dealing with building data infrastructure.A focus within "Ingenierie des Systèmes d\'Information et de Télécommunications" and potentially "Génie Industriel," dealing with building data infrastructure.',1),(4,'Water and Environmental Engineering','Possibly a specialization within "Génie Civil" or a research area, focusing on sustainable water management and environmental protection.',4),(5,'Energy and Renewable Energies','Could be a specialization within "Génie Électrique" or "Génie Mécanique," concentrating on power, efficiency, and green energy sources.',4);
/*!40000 ALTER TABLE `filiere` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  `semester` enum('s1','s2','s3','s4','s5','s6') DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `code_module` varchar(20) NOT NULL,
  `volume_cours` smallint(6) DEFAULT 0,
  `volume_td` smallint(6) DEFAULT 0,
  `volume_tp` smallint(6) DEFAULT 0,
  `volume_autre` smallint(6) DEFAULT 0,
  `evaluation` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id_module`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `code_module` (`code_module`),
  KEY `id_filiere` (`id_filiere`),
  CONSTRAINT `module_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Français','Module de français avancé','s1',3,1,'M1.1',10,5,0,0,1),(2,'Anglais','Module d\'anglais avancé','s1',3,1,'M1.2',8,6,0,0,1),(3,'Mathématiques','Analyse mathématique','s2',4,1,'M3.1',20,10,0,0,2),(17,'tqwvm','mbasdd','s3',22,1,'M17',13,0,0,0,0),(18,'asdjd','ghhbnjn','s1',2,1,'M3.2',4545,0,0,0,2),(19,'jhajb','jknmnm','s1',77,1,'M19.1',45,0,0,0,3);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) NOT NULL,
  `id_vacataire` int(11) DEFAULT NULL,
  `id_professor` int(11) DEFAULT NULL,
  `date_upload` date NOT NULL,
  `session` enum('normal','ratt') NOT NULL,
  `file_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id_note`),
  KEY `id_module` (`id_module`),
  KEY `id_professor` (`id_professor`),
  KEY `id_vacataire` (`id_vacataire`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`),
  CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `notes_ibfk_3` FOREIGN KEY (`id_vacataire`) REFERENCES `vacataire` (`id_vacataire`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`id_professor` is not null and `id_vacataire` is null or `id_professor` is null and `id_vacataire` is not null)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (10,1,NULL,3,'2025-04-20','ratt','1387697393'),(12,5,NULL,4,'2025-04-29','ratt','1908555592.pdf'),(13,4,NULL,2,'2025-05-01','ratt','868599667.pdf');
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
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
  `image_url` text DEFAULT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `content` varchar(400) NOT NULL,
  PRIMARY KEY (`id_notification`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (11,1,'2024-02-01 09:00:00','New Module Assignment',NULL,'read','You have been assigned to teach Database Management'),(12,1,'2024-02-01 10:30:00','Grade Submission Reminder',NULL,'read','Please submit grades for Programming 101 by Friday'),(13,1,'2024-02-02 11:15:00','Department Meeting',NULL,'read','Monthly department meeting scheduled for next week'),(14,1,'2024-02-02 14:00:00','Schedule Update',NULL,'read','Your teaching schedule has been updated'),(15,1,'2024-02-03 09:45:00','System Maintenance',NULL,'read','System will be down for maintenance on Sunday'),(16,2,'2025-04-16 22:19:28','Welcome to E-service',NULL,'read','Please change your temporary password as soon as possible for account security. You can do this by going to your profile settings.'),(24,3,'2025-04-19 00:39:11','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : Web Dev'),(25,3,'2025-04-19 00:42:15','Affectation enregistrée',NULL,'read','Vos choix de modules ont bien été enregistrés : C++. ⚠️ Attention : votre charge horaire (230 h) dépasse le maximum autorisé (150 h).'),(26,3,'2025-04-19 12:51:05','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : Web Dev'),(27,3,'2025-04-19 12:51:30','Affectation enregistrée',NULL,'read','Vos choix de modules ont bien été enregistrés : C++. ⚠️ Attention : votre charge horaire (230 h) dépasse le maximum autorisé (150 h).'),(28,3,'2025-04-19 13:51:21','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : C++. ⚠️ Attention : votre charge horaire (230 h) dépasse le maximum autorisé (150 h).'),(29,3,'2025-04-19 15:28:13','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : C++. ⚠️ Attention : votre charge horaire (230 h) dépasse le maximum autorisé (150 h).'),(30,3,'2025-04-20 20:14:52','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : test. ⚠️ Attention : votre charge horaire (330 h) dépasse le maximum autorisé (150 h).'),(31,3,'2025-04-20 20:21:31','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : test. ⚠️ Attention : votre charge horaire (330 h) dépasse le maximum autorisé (150 h).'),(32,3,'2025-04-20 22:26:17','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : test. ⚠️ Attention : votre charge horaire (330 h) dépasse le maximum autorisé (150 h).'),(33,3,'2025-04-20 22:47:43','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : francais, engalais. ⚠️ Attention : votre charge horaire (520 h) dépasse le maximum autorisé (150 h).'),(40,4,'2025-04-24 15:52:18','Bienvenue sur E-service',NULL,'read','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.'),(41,4,'2025-04-24 16:22:39','Affectation enregistrée',NULL,'read','Vos choix de modules ont bien été enregistrés : Web Dev. ⚠️ Attention : votre charge horaire (120 h) est inférieure au minimum requis (150 h).'),(42,4,'2025-04-28 22:30:59','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : mecanique, francais. ⚠️ Attention : votre charge horaire (310 h) dépasse le maximum autorisé (200 h).'),(43,4,'2025-04-28 22:32:24','🎓 Module validé',NULL,'unread','Votre demande pour le module \'mecanique\' a été validée pour l\'année 2025.'),(44,4,'2025-04-28 22:32:26','🎓 Module validé',NULL,'unread','Votre demande pour le module \'Web Dev\' a été validée pour l\'année 2025.'),(45,2,'2025-04-28 22:33:05','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : francais. ⚠️ Attention : votre charge horaire (90 h) est inférieure au minimum requis (120 h).'),(46,2,'2025-04-28 22:33:15','🎓 Module validé',NULL,'unread','Votre demande pour le module \'francais\' a été validée pour l\'année 2025.'),(47,3,'2025-04-29 00:14:33','Affectation enregistrée',NULL,'read','Vos choix de modules ont bien été enregistrés : Web Dev, C++, mecanique. ⚠️ Attention : votre charge horaire (330 h) dépasse le maximum autorisé (150 h).'),(48,3,'2025-04-29 00:18:00','🎓 Module validé',NULL,'unread','Votre demande pour le module \'Web Dev\' a été validée pour l\'année 2025.'),(49,3,'2025-04-29 00:18:07','🎓 Module validé',NULL,'unread','Votre demande pour le module \'mecanique\' a été validée pour l\'année 2025.'),(50,2,'2025-04-29 00:20:57','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : francais. ⚠️ Attention : votre charge horaire (90 h) est inférieure au minimum requis (120 h).'),(51,4,'2025-04-29 00:23:54','🎓 Module validé',NULL,'unread','Le module \'engalais\' a été affecté à vous pour l\'année 2025 par le chef de département.'),(53,2,'2025-05-06 20:42:57','Affectation enregistrée',NULL,'read','Vos choix de modules ont bien été enregistrés : Anglais. ⚠️ Attention : votre charge horaire (0 h) est inférieure au minimum requis (120 h).'),(55,13,'2025-05-08 18:54:06','Bienvenue sur E-service',NULL,'unread','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.'),(56,14,'2025-05-08 18:55:37','Bienvenue sur E-service',NULL,'unread','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.');
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
INSERT INTO `professor` VALUES (2,130,120,'chef_deparetement',1),(3,150,120,'coordonnateur',1),(4,200,150,'coordonnateur',1);
/*!40000 ALTER TABLE `professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `speciality`
--

DROP TABLE IF EXISTS `speciality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speciality` (
  `id_speciality` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `id_deparetement` int(11) NOT NULL,
  PRIMARY KEY (`id_speciality`),
  KEY `id_deparetement` (`id_deparetement`),
  CONSTRAINT `speciality_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `speciality`
--

LOCK TABLES `speciality` WRITE;
/*!40000 ALTER TABLE `speciality` DISABLE KEYS */;
INSERT INTO `speciality` VALUES (1,'English Language',1),(2,'French Language',1),(3,'Software Tools',1),(4,'Scientific Writing',1),(5,'Communication Skills',1),(6,'Project Management',1),(7,'Research Methods',1),(14,'English Language',1),(15,'French Language',1),(16,'Software Tools',1),(17,'Scientific Writing',1),(18,'Communication Skills',1),(19,'Project Management',1),(20,'Research Methods',1),(21,'English Language',2),(22,'French Language',2),(23,'Mathematics',2),(24,'Statistics',2),(25,'Scientific Writing',2),(26,'Research Methods',2),(27,'English Language',3),(28,'French Language',3),(29,'Physics',3),(30,'Mathematics',3),(31,'Scientific Writing',3),(32,'Research Methods',3),(33,'English Language',4),(34,'French Language',4),(35,'Project Management',4),(36,'Software Tools',4),(37,'Physics',4),(38,'Mathematics',4),(39,'Communication Skills',4),(40,'English Language',5),(41,'French Language',5),(42,'Communication Skills',5),(43,'Project Management',5),(44,'Statistics',5),(45,'Research Methods',5);
/*!40000 ALTER TABLE `speciality` ENABLE KEYS */;
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
  `img` text DEFAULT 'default.webp',
  `CIN` char(8) NOT NULL,
  `email` char(100) NOT NULL,
  `role` enum('professor','vacataire','admin') NOT NULL,
  `password` char(255) NOT NULL,
  `phone` char(10) NOT NULL,
  `address` text NOT NULL,
  `birth_date` date NOT NULL,
  `creation_date` datetime NOT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `CIN` (`CIN`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'ADMIN','ENSAH','default.webp','rc12435','adminEnsah@eservice.com','admin','$2y$10$CWflyVgtOuJjEvH5.BHuAeI8kCNhJGZJ1OM4pKy.C0Pg9BY3y1h/6','0653646266','ensah  alhoceima','2000-01-01','2025-03-03 00:00:00','active'),(2,'hassan','hassan','2_3957213016242711.png','PB28536','hassan@gmail.com','professor','$2y$10$pPOO9pFsdt0GvB6T34oO..XmD3ZGNIBuYoFoHYKeTATh/ZcKOBtr6','123456789','morocco alhociema','2000-12-03','2025-04-16 22:19:28','active'),(3,'hassan','hassan','default.webp','PB234323','hassanvivo25@gmail.com','professor','$2y$10$pPOO9pFsdt0GvB6T34oO..XmD3ZGNIBuYoFoHYKeTATh/ZcKOBtr6','123456789','morocco alhociema','2001-02-02','2025-04-16 22:23:25','active'),(4,'AYOUB','Coordonnateur','4_8702589800169008.png','R123456','ayoub_coord@gmail.com','professor','$2y$10$fyhNM0ptoJxAnsXwYjo4ruvZOobJeE7/5C3rDa423LMM33YXsC/xy','688171625','Beni Bouayach , AL Hoceima','2001-04-24','2025-04-24 15:52:18','active'),(13,'Moad','Abdou','default.webp','R161034','elabdellaoui.mod@etu.uae.ma','vacataire','$2y$10$JydN9h72WhPlljPw9crtD.1Sor8zPA/OAtQhCzLrhkE0ImkqCQjSW','612345678','test alo alo alo','2001-05-03','2025-05-08 18:54:06','active'),(14,'Moad','Abdou','default.webp','R161000','elabdellaoui.mod@etu.uae.ac.ma','vacataire','$2y$10$GeU8scMCsbtAnTUBZhrqVeY9ejfUHvipiMHO6e5r6AijP5rdl9MaW','612345678','test alo alo alo','2001-05-02','2025-05-08 18:55:37','active');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacataire`
--

DROP TABLE IF EXISTS `vacataire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacataire` (
  `id_vacataire` int(11) NOT NULL,
  `id_coordonnateur` int(11) DEFAULT NULL,
  `speciality` int(11) NOT NULL,
  UNIQUE KEY `id_vacataire` (`id_vacataire`),
  KEY `id_coordonnateur` (`id_coordonnateur`),
  KEY `speciality` (`speciality`),
  CONSTRAINT `vacataire_ibfk_1` FOREIGN KEY (`id_vacataire`) REFERENCES `user` (`id_user`),
  CONSTRAINT `vacataire_ibfk_2` FOREIGN KEY (`id_coordonnateur`) REFERENCES `coordonnateur` (`id_coordonnateur`),
  CONSTRAINT `vacataire_ibfk_3` FOREIGN KEY (`speciality`) REFERENCES `speciality` (`id_speciality`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacataire`
--

LOCK TABLES `vacataire` WRITE;
/*!40000 ALTER TABLE `vacataire` DISABLE KEYS */;
INSERT INTO `vacataire` VALUES (13,4,7),(14,4,5);
/*!40000 ALTER TABLE `vacataire` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-10 13:26:33