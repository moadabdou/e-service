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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'2025-06-11 22:16:45','Un nouveau professeur Moad Abdou a été ajouté','fa-user-plus'),(2,'2025-06-11 22:18:13','Un nouveau professeur Hassan Hassan a été ajouté','fa-user-plus'),(3,'2025-06-11 22:18:57','Un nouveau professeur ayoube ayoube a été ajouté','fa-user-plus'),(4,'2025-06-11 22:22:24','Le professeur Hassan Hassan a été nommé chef de département Département Mathématiques et Informatique par l\'administrateur ADMIN ENSAH.','fa-user-check'),(5,'2025-06-11 22:22:36','Le professeur Moad Abdou a été nommé coordinateur de la filière Génie Informatique par l\'administrateur ADMIN ENSAH.','fa-user-check');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announces`
--

LOCK TABLES `announces` WRITE;
/*!40000 ALTER TABLE `announces` DISABLE KEYS */;
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
INSERT INTO `choix_module` VALUES (24,1,'2025-06-11',NULL,'in progress'),(24,2,'2025-06-11',NULL,'in progress'),(24,7,'2025-06-11',NULL,'in progress'),(24,8,'2025-06-11',NULL,'in progress');
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
INSERT INTO `coordonnateur` VALUES (22,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deparetement`
--

LOCK TABLES `deparetement` WRITE;
/*!40000 ALTER TABLE `deparetement` DISABLE KEYS */;
INSERT INTO `deparetement` VALUES (1,'Département Mathématiques et Informatique','Le Département Mathématiques et Informatique forme des étudiants dans les domaines fondamentaux et appliqués des mathématiques, de l’informatique, des systèmes intelligents, des algorithmes, et de l’analyse de données. Il prépare les étudiants à des carrières en recherche, développement logiciel, intelligence artificielle et cybersécurité.'),(2,'Département Génie Civil Energétique et Environneme','Le Département GCEE regroupe les spécialités du génie civil, de l’énergie, et de l’environnement. Il forme des ingénieurs capables de concevoir des infrastructures durables, d’optimiser les systèmes énergétiques, et de répondre aux défis environnementaux à travers des solutions innovantes et écologiques.'),(3,'Années Préparatoires','Les Années Préparatoires constituent un cycle fondamental destiné à doter les étudiants des bases solides en mathématiques, physique, chimie et informatique. Elles permettent d’acquérir des méthodes de travail rigoureuses et préparent efficacement à l’intégration dans les différents départements d’ingénierie.');
/*!40000 ALTER TABLE `deparetement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emploi_temps_upload`
--

DROP TABLE IF EXISTS `emploi_temps_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emploi_temps_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_filiere` int(11) NOT NULL,
  `semestre` enum('s1','s2','s3','s4','s5','s6') NOT NULL,
  `annee` year(4) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin_fichier` text NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_emploi_filiere` (`id_filiere`),
  CONSTRAINT `fk_emploi_filiere` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emploi_temps_upload`
--

LOCK TABLES `emploi_temps_upload` WRITE;
/*!40000 ALTER TABLE `emploi_temps_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `emploi_temps_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feature_deadlines`
--

DROP TABLE IF EXISTS `feature_deadlines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feature_deadlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feature` enum('choose_modules','upload_notes') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_deadlines`
--

LOCK TABLES `feature_deadlines` WRITE;
/*!40000 ALTER TABLE `feature_deadlines` DISABLE KEYS */;
INSERT INTO `feature_deadlines` VALUES (1,'choose_modules','2025-06-11 22:27:00','2025-06-13 22:27:00','open','2025-06-11 21:27:56'),(2,'upload_notes','2025-06-11 22:28:00','2025-06-13 22:28:00','open','2025-06-11 21:28:13');
/*!40000 ALTER TABLE `feature_deadlines` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filiere`
--

LOCK TABLES `filiere` WRITE;
/*!40000 ALTER TABLE `filiere` DISABLE KEYS */;
INSERT INTO `filiere` VALUES (1,'Génie Informatique','Ce programme vise à doter les étudiants de connaissances avancées et de compétences pratiques dans divers aspects de l\'ingénierie informatique, y compris le développement de logiciels, les algorithmes, l\'intelligence artificielle, les réseaux et la gestion de bases de données. Les diplômés sont préparés à concevoir, développer et maintenir des systèmes informatiques complexes et des solutions inno',1),(2,'Ingénierie des Données','Le programme d\'Ingénierie des Données est conçu pour former des spécialistes dans la collecte, le stockage, le traitement et l\'analyse de grands ensembles de données. Les étudiants apprennent la modélisation des données, les technologies du big data, l\'entreposage de données et la gouvernance des données, les préparant à des rôles en architecture de données, en support à la science des données et ',1),(3,'Transformation Digitale et Intelligence Artificiel','Cette spécialisation explore l\'intégration stratégique des technologies numériques et de l\'intelligence artificielle pour stimuler la transformation des entreprises. Les étudiants acquièrent une expertise en développement d\'IA, en apprentissage automatique, en analyse de données pour la prise de décision et en compréhension de l\'impact du changement numérique sur les organisations, les préparant à',1),(4,'Année Préparatoire','L\'année préparatoire propose un programme fondamental dans les matières scientifiques et d\'ingénierie de base, y compris les mathématiques, la physique et les bases de l\'informatique. Elle vise à renforcer la base académique des étudiants et à développer les compétences essentielles d\'analyse et de résolution de problèmes nécessaires aux études avancées dans diverses disciplines de l\'ingénierie.',3),(5,'Génie Civil','Le programme de Génie Civil se concentre sur la conception, la construction et la maintenance de projets d\'infrastructure tels que les bâtiments, les routes, les ponts et les systèmes d\'eau. Les étudiants acquièrent des connaissances en analyse structurelle, en ingénierie géotechnique, en urbanisme et en gestion de la construction, les préparant à contribuer au développement durable et aux environ',2),(6,'Génie Énergétique et Environnement','Ce programme est dédié à la formation d\'ingénieurs en systèmes énergétiques et en gestion environnementale. Il couvre les technologies des énergies renouvelables, l\'efficacité énergétique, le contrôle de la pollution et la gestion durable des ressources. Les diplômés sont équipés pour concevoir et mettre en œuvre des solutions respectueuses de l\'environnement et économes en énergie pour les indust',2),(7,'Génie Électrique, Énergie Renouvelable et Réseaux','Cette spécialisation combine les principes de l\'ingénierie électrique avec un accent sur les sources d\'énergie renouvelables et les technologies de réseaux intelligents. Les étudiants apprennent les systèmes d\'alimentation électrique, la production distribuée, les compteurs intelligents et l\'optimisation des réseaux, les préparant à des carrières dans le secteur de l\'énergie en rapide évolution.',2);
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
  `id_filiere` int(11) NOT NULL DEFAULT 1,
  `id_filiere` int(11) NOT NULL DEFAULT 1,
  `code_module` varchar(20) NOT NULL,
  `volume_cours` smallint(6) DEFAULT 0,
  `volume_td` smallint(6) DEFAULT 0,
  `volume_tp` smallint(6) DEFAULT 0,
  `volume_autre` smallint(6) DEFAULT 0,
  `evaluation` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id_module`),
  KEY `id_filiere` (`id_filiere`),
  CONSTRAINT `module_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Architecture des ordinateurs','Ce module, intitulé \'Architecture des ordinateurs\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de l\'architecture des ordinateurs et prépare les étudiants aux défis du domaine. ','s1',6,1,'M111',26,16,16,4,0),(2,'Langage C avancé et structures de données','Ce module, intitulé \'Langage C avancé et structures de données\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de le langage c avancé et structures de données et prépare les étudiants aux défis du domaine. ','s1',6,1,'M112',26,16,18,0,0),(3,'Recherche opérationnelle et théorie des graphes','Ce module, intitulé \'Recherche opérationnelle et théorie des graphes\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de recherche opérationnelle et théorie des graphes et prépare les étudiants aux défis du domaine. ','s1',6,1,'M113',26,24,12,0,0),(4,'Systèmes d’Information et Bases de Données Relatio','Ce module, intitulé \'Systèmes d’Information et Bases de Données Relationnelles\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de systèmes d’information et bases de données relationnelles et prépare les étudiants aux défis du domaine. ','s1',6,1,'M114',26,24,12,0,0),(5,'Réseaux informatiques','Ce module, intitulé \'Réseaux informatiques\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de réseaux informatiques et prépare les étudiants aux défis du domaine. ','s1',6,1,'M115',26,18,14,4,0),(6,'Culture and Art skills','Ce module, intitulé \'Culture and Art skills\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de culture and art skills et prépare les étudiants aux défis du domaine. ','s1',3,1,'M116',26,10,0,9,0),(7,'Langues Etrangéres (Français)','Ce module, intitulé \'Langues Etrangéres (Français)\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues étrangéres (français) et prépare les étudiants aux défis du domaine. ','s1',3,1,'M117.1',20,6,3,0,1),(8,'Langues Etrangéres (Anglais)','Ce module, intitulé \'Langues Etrangéres (Anglais)\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues étrangéres (anglais) et prépare les étudiants aux défis du domaine. ','s1',3,1,'M117.2',20,6,3,0,1),(9,'Architecture Logicielle et UML','Ce module, intitulé \'Architecture Logicielle et UML\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de l\'architecture logicielle et uml et prépare les étudiants aux défis du domaine. ','s2',6,1,'M121',26,16,10,10,0),(10,'Web1 : Technologies de Web et PHP5','Ce module, intitulé \'Web1 : Technologies de Web et PHP5\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de web1 : technologies de web et php5 et prépare les étudiants aux défis du domaine. ','s2',6,1,'M122',26,10,16,10,0),(11,'Programmation Orientée Objet C++','Ce module, intitulé \'Programmation Orientée Objet C++\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de programmation orientée objet c++ et prépare les étudiants aux défis du domaine. ','s2',6,1,'M123',26,16,10,10,0),(12,'Linux et programmation systéme','Ce module, intitulé \'Linux et programmation systéme\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de linux et programmation systéme et prépare les étudiants aux défis du domaine. ','s2',6,1,'M124',26,16,10,10,0),(13,'Algorithmique Avancée et complexité','Ce module, intitulé \'Algorithmique Avancée et complexité\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de algorithmique avancée et complexité et prépare les étudiants aux défis du domaine. ','s2',6,1,'M125',26,26,4,6,0),(14,'Prompt ingeniering for developpers','Ce module, intitulé \'Prompt ingeniering for developpers\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de prompt ingeniering for developpers et prépare les étudiants aux défis du domaine. ','s2',6,1,'M126',26,26,6,4,0),(15,'Langues,Communication et TIC -fr','Ce module, intitulé \'Langues,Communication et TIC -fr\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues,communication et tic -fr et prépare les étudiants aux défis du domaine. ','s2',6,1,'M127.1',20,6,3,0,2),(16,'Langues,Communication et TIC- Ang','Ce module, intitulé \'Langues,Communication et TIC- Ang\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues,communication et tic- ang et prépare les étudiants aux défis du domaine. ','s2',6,1,'M127.2',20,6,3,0,2),(17,'Python pour les sciences de données','Ce module, intitulé \'Python pour les sciences de données\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de python pour les sciences de données et prépare les étudiants aux défis du domaine. ','s3',6,1,'M31',28,0,36,0,0),(18,'Programmation Java Avancée','Ce module, intitulé \'Programmation Java Avancée\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de programmation java avancée et prépare les étudiants aux défis du domaine. ','s3',6,1,'M32',24,8,32,0,0),(19,'Langues et Communication -FR','Ce module, intitulé \'Langues et Communication -FR\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues et communication -fr et prépare les étudiants aux défis du domaine. ','s3',2,1,'M33.1',21,0,11,0,3),(20,'Langues et Communication- Ang','Ce module, intitulé \'Langues et Communication- Ang\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues et communication- ang et prépare les étudiants aux défis du domaine. ','s3',2,1,'M33.2',21,10,0,0,3),(21,'Langues et Communication- Espagnol','Ce module, intitulé \'Langues et Communication- Espagnol\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de langues et communication- espagnol et prépare les étudiants aux défis du domaine. ','s3',2,1,'M33.3',21,10,0,0,3),(23,'Linux et programmation système 2','Ce module, intitulé \'Linux et programmation système\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de linux et programmation système et prépare les étudiants aux défis du domaine. ','s3',6,1,'M34',21,16,27,0,0),(24,'Administration des Bases de données Avancées','Ce module, intitulé \'Administration des Bases de données Avancées\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de administration des bases de données avancées et prépare les étudiants aux défis du domaine. ','s3',6,1,'M35',26,4,34,0,0),(25,'Administration réseaux et systèmes','Ce module, intitulé \'Administration réseaux et systèmes\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de administration réseaux et systèmes et prépare les étudiants aux défis du domaine. ','s3',6,1,'M36',27,15,22,0,0),(26,'Entreprenariat 2 - Contrôle gestion','Ce module, intitulé \'Entreprenariat 2 - Contrôle gestion\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de entreprenariat 2 - contrôle gestion et prépare les étudiants aux défis du domaine. ','s4',3,1,'M41.1',21,18,0,0,4),(27,'Entreprenariat 2 -Marketing fondamental','Ce module, intitulé \'Entreprenariat 2 -Marketing fondamental\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de entreprenariat 2 -marketing fondamental et prépare les étudiants aux défis du domaine. ','s4',3,1,'M41.2',25,0,0,0,4),(28,'Machine Learning','Ce module, intitulé \'Machine Learning\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de machine learning et prépare les étudiants aux défis du domaine. ','s4',6,1,'M42',21,20,23,0,0),(29,'Gestion de projet','Ce module, intitulé \'Gestion de projet\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de gestion de projet et prépare les étudiants aux défis du domaine. ','s4',3,1,'M43.1',16,6,16,0,5),(30,'Génie logiciel','Ce module, intitulé \'Génie logiciel\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de génie logiciel et prépare les étudiants aux défis du domaine. ','s4',3,1,'M43.2',12,6,0,8,5),(31,'Crypto-systèmes','Ce module, intitulé \'Crypto-systèmes\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de crypto-systèmes et prépare les étudiants aux défis du domaine. ','s4',3,1,'M44.1',15,10,4,0,6),(32,'sécurité Informatique','Ce module, intitulé \'sécurité Informatique\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de sécurité informatique et prépare les étudiants aux défis du domaine. ','s4',3,1,'M44.2',15,10,10,0,6),(33,'Frameworks Java EE avancés','Ce module, intitulé \'Frameworks Java EE avancés\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de frameworks java ee avancés et prépare les étudiants aux défis du domaine. ','s4',0,1,'M45.1',15,10,4,0,7),(34,'.Net','Ce module, intitulé \'.Net\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de .net et prépare les étudiants aux défis du domaine. ','s4',0,1,'M45.2',15,10,10,0,7),(35,'Web 2 : Applications Web modernes','Ce module, intitulé \'Web 2 : Applications Web modernes\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de web 2 : applications web modernes et prépare les étudiants aux défis du domaine. ','s4',6,1,'M46',21,15,28,0,0),(36,'Système embarqué et temps réel','Ce module, intitulé \'Système embarqué et temps réel\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de système embarqué et temps réel et prépare les étudiants aux défis du domaine. ','s5',6,1,'M51',25,25,14,0,0),(37,'Développement des applications mobiles','Ce module, intitulé \'Développement des applications mobiles\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de développement des applications mobiles et prépare les étudiants aux défis du domaine. ','s5',6,1,'M52',28,0,36,0,0),(38,'Virtualisation','Ce module, intitulé \'Virtualisation\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de virtualisation et prépare les étudiants aux défis du domaine. ','s5',3,1,'M53.1',10,4,12,0,8),(39,'Cloud Computing','Ce module, intitulé \'Cloud Computing\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de cloud computing et prépare les étudiants aux défis du domaine. ','s5',3,1,'M53.2',12,8,18,0,8),(40,'Analyse et conception des systèmes décisionnels','Ce module, intitulé \'Analyse et conception des systèmes décisionnels\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de analyse et conception des systèmes décisionnels et prépare les étudiants aux défis du domaine. ','s5',6,1,'M54',28,12,24,0,0),(41,'Enterprise Resource Planning ERP','Ce module, intitulé \'Enterprise Resource Planning ERP\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de enterprise resource planning erp et prépare les étudiants aux défis du domaine. ','s5',6,1,'M55',22,12,30,0,0),(42,'Ingénierie logicielle, Qualité, Test et Intégratio','Ce module, intitulé \'Ingénierie logicielle, Qualité, Test et Intégration\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de ingénierie logicielle, qualité, test et intégration et prépare les étudiants aux défis du domaine. ','s5',6,1,'M56',21,18,25,0,0),(43,'Ingénierie de l’information et des connaissances','Ce module, intitulé \'Ingénierie de l’information et des connaissances\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de ingénierie de l’information et des connaissances et prépare les étudiants aux défis du domaine. ','s5',6,1,'M57',28,12,24,0,0),(44,'Business Intelligence & Veille Stratégique','Ce module, intitulé \'Business Intelligence & Veille Stratégique\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de business intelligence & veille stratégique et prépare les étudiants aux défis du domaine. ','s5',6,1,'M58',24,16,24,0,0),(45,'Data Mining','Ce module, intitulé \'Data Mining\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de data mining et prépare les étudiants aux défis du domaine. ','s5',6,1,'M59',26,14,24,0,0),(46,'Entreprenariat 3 -RH','Ce module, intitulé \'Entreprenariat 3 -RH\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de entreprenariat 3 -rh et prépare les étudiants aux défis du domaine. ','s5',4,1,'M510.1',30,0,0,0,9),(47,'Entreprenariat 3 - Gestion financiere','Ce module, intitulé \'Entreprenariat 3 - Gestion financiere\', fait partie du programme de Génie Informatique. Il couvre des aspects fondamentaux de entreprenariat 3 - gestion financiere et prépare les étudiants aux défis du domaine. ','s5',2,1,'M510.2',18,16,0,0,9),(48,'Analyse numérique matricielle','Ce module, intitulé \'Analyse numérique matricielle\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'analyse numérique matricielle et prépare les étudiants aux défis du domaine.','s1',3,2,'M111.1',15,10,5,0,10),(49,'statistique inférentielle','Ce module, intitulé \'Statistique inférentielle\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la statistique inférentielle et prépare les étudiants aux défis du domaine.','s1',3,2,'M111.2',15,10,5,0,10),(51,'Théorie des langages et compilation','Ce module, intitulé \'Théorie des langages et compilation\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la théorie des langages et de la compilation et prépare les étudiants aux défis du domaine.','s1',6,2,'M112',26,16,10,0,0),(52,'Systèmes d’Information et Bases de données','Ce module, intitulé \'Systèmes d’Information et Bases de données\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des systèmes d\'information et des bases de données et prépare les étudiants aux défis du domaine.','s1',6,2,'M113',26,16,10,0,0),(53,'Relationnelles','Ce module, intitulé \'Relationnelles\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des bases de données relationnelles et prépare les étudiants aux défis du domaine.','s1',6,2,'M114',26,16,10,0,0),(54,'Architectures des ordinateurs et systèmes d’exploi','Ce module, intitulé \'Architectures des ordinateurs et systèmes d’exploitation\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des architectures des ordinateurs et des systèmes d\'exploitation et prépare les étudiants aux défis du domaine.','s1',6,2,'M115',26,18,14,4,0),(55,'Structure de données et algorithmique avancée','Ce module, intitulé \'Structure de données et algorithmique avancée\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la structure de données et de l\'algorithmique avancée et prépare les étudiants aux défis du domaine.','s1',6,2,'M116',26,24,12,0,0),(56,'Anglais','Ce module, intitulé \'Anglais\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'anglais et prépare les étudiants aux défis du domaine.','s1',3,2,'M117.1',20,6,3,0,11),(57,'Français','Ce module, intitulé \'Français\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du français et prépare les étudiants aux défis du domaine.','s1',3,2,'M117.2',20,6,3,0,11),(58,'Programmation Python Bases du Web','Ce module, intitulé \'Programmation Python Bases du Web\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la programmation Python et des bases du Web et prépare les étudiants aux défis du domaine.','s2',6,2,'M126',26,16,10,0,0),(59,'Data mining','Ce module, intitulé \'Data mining\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du data mining et prépare les étudiants aux défis du domaine.','s2',6,2,'M127',26,16,10,0,0),(60,'Statistique en grande dimension','Ce module, intitulé \'Statistique en grande dimension\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la statistique en grande dimension et prépare les étudiants aux défis du domaine.','s2',6,2,'M125',26,16,10,0,0),(61,'Programmation orientée objet java','Ce module, intitulé \'Programmation orientée objet java\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la programmation orientée objet Java et prépare les étudiants aux défis du domaine.','s2',6,2,'M121',26,16,10,0,0),(62,'Administration et optimisation des bases de donnée','Ce module, intitulé \'Administration et optimisation des bases de données\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'administration et de l\'optimisation des bases de données et prépare les étudiants aux défis du domaine.','s2',6,2,'M122',26,16,10,0,0),(63,'Communication Professionnelle II : Anglais','Ce module, intitulé \'Communication Professionnelle II : Anglais\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la communication professionnelle en anglais et prépare les étudiants aux défis du domaine.','s2',3,2,'M123.1',20,6,3,0,12),(64,'Communication Professionnelle II : Espagnol','Ce module, intitulé \'Communication Professionnelle II : Espagnol\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la communication professionnelle en espagnol et prépare les étudiants aux défis du domaine.','s2',3,2,'M123.2',20,6,3,0,12),(65,'Entreprenariat -I-','Ce module, intitulé \'Entreprenariat -I-\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'entreprenariat et prépare les étudiants aux défis du domaine.','s2',3,2,'M124',26,10,0,9,0),(66,'Inteligence Artificielle I: Maching Learning','Ce module, intitulé \'Intelligence Artificielle I: Machine Learning\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'intelligence artificielle et de l\'apprentissage automatique et prépare les étudiants aux défis du domaine.','s3',6,2,'M31',24,10,24,0,0),(67,'Modélisation stochastique','Ce module, intitulé \'Modélisation stochastique\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la modélisation stochastique et prépare les étudiants aux défis du domaine.','s3',3,2,'M32.1',13,10,7,0,13),(68,'Technique Mathématiques d\'Optimisation','Ce module, intitulé \'Technique Mathématiques d\'Optimisation\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des techniques mathématiques d\'optimisation et prépare les étudiants aux défis du domaine.','s3',3,2,'M32.2',13,10,7,0,13),(69,'Architecture Logicielle et UML','Ce module, intitulé \'Architecture Logicielle et UML\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'architecture logicielle et UML et prépare les étudiants aux défis du domaine.','s3',6,2,'M33',24,10,24,0,0),(70,'Fondements du Big Data','Ce module, intitulé \'Fondements du Big Data\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des fondements du Big Data et prépare les étudiants aux défis du domaine.','s3',6,2,'M34',24,10,24,0,0),(71,'Français','Ce module, intitulé \'Français\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du français et prépare les étudiants aux défis du domaine.','s3',2,2,'M35.1',12,10,0,10,14),(72,'Anglais','Ce module, intitulé \'Anglais\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'anglais et prépare les étudiants aux défis du domaine.','s3',2,2,'M35.2',12,10,0,10,14),(73,'SoftSkills','Ce module, intitulé \'SoftSkills\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des soft skills et prépare les étudiants aux défis du domaine.','s3',2,2,'M35.3',10,10,0,10,14),(74,'Bases de données Avancées','Ce module, intitulé \'Bases de données Avancées\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des bases de données avancées et prépare les étudiants aux défis du domaine.','s3',6,2,'M36',24,10,24,0,0),(75,'Big Data Avancée','Ce module, intitulé \'Big Data Avancée\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du Big Data avancé et prépare les étudiants aux défis du domaine.','s4',6,2,'M41',24,10,24,0,0),(76,'Inelligence artificielle-II- Deep Learning','Ce module, intitulé \'Intelligence Artificielle II: Deep Learning\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'intelligence artificielle et du Deep Learning et prépare les étudiants aux défis du domaine.','s4',6,2,'M42',24,8,32,0,0),(77,'Data Werhaus et Data Lake','Ce module, intitulé \'Data Warehouse et Data Lake\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du Data Warehouse et du Data Lake et prépare les étudiants aux défis du domaine.','s4',4,2,'M43',21,0,21,0,0),(78,'Applicataions Web avancées avec Java et spring','Ce module, intitulé \'Applications Web avancées avec Java et Spring\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux des applications Web avancées avec Java et Spring et prépare les étudiants aux défis du domaine.','s4',6,2,'M44',21,16,27,0,0),(79,'TAL','Ce module, intitulé \'Traitement Automatique du Langage (TAL)\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du traitement automatique du langage et prépare les étudiants aux défis du domaine.','s4',6,2,'M45',26,4,34,0,0),(80,'Entreprenariat-II','Ce module, intitulé \'Entreprenariat II\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'entreprenariat et prépare les étudiants aux défis du domaine.','s4',6,2,'M46',27,15,22,0,0),(81,'Big Data Visualisation & Cloud Computing','Ce module, intitulé \'Visualisation Big Data & Cloud Computing\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la visualisation de données massives et du cloud computing et prépare les étudiants aux défis du domaine.','s5',6,2,'M51',28,12,24,0,0),(82,'Business inteligence & Veille Stratégique','Ce module, intitulé \'Business Intelligence & Veille Stratégique\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la veille stratégique et de l\'intelligence économique et prépare les étudiants aux défis du domaine.','s5',6,2,'M510',24,16,24,0,0),(83,'Entreprenariat-3-','Ce module, intitulé \'Entreprenariat III\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'entreprenariat et prépare les étudiants aux défis du domaine.','s5',4,2,'M511',22,12,30,0,0),(84,'Social Network Mining','Ce module, intitulé \'Social Network Mining\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de l\'analyse des réseaux sociaux et prépare les étudiants aux défis du domaine.','s5',6,2,'M512',26,14,24,0,0),(85,'Web Marketing et CRM','Ce module, intitulé \'Web Marketing et CRM\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux du marketing numérique et de la gestion de la relation client et prépare les étudiants aux défis du domaine.','s5',6,2,'M513',28,12,24,0,0),(86,'Transformation Digital','Ce module, intitulé \'Transformation Digitale\', fait partie du programme d\'Ingénierie des Données. Il couvre des aspects fondamentaux de la transformation numérique et prépare les étudiants aux défis du domaine.','s5',6,2,'M514',21,18,25,0,0),(87,'Théorie des langages et compilation','Ce module, intitulé \'Théorie des langages et compilation\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les concepts fondamentaux des langages formels et des techniques de compilation.','s1',6,3,'M111',26,18,8,10,0),(88,'Systèmes d’Information et Bases de Données','Ce module, intitulé \'Systèmes d’Information et Bases de Données\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il aborde la conception, la gestion et l\'optimisation des systèmes d\'information et des bases de données.','s1',6,3,'M112',26,10,16,10,0),(89,'Structure de données et Algorithmique avancée','Ce module, intitulé \'Structure de données et Algorithmique avancée\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore les structures de données complexes et les algorithmes efficaces pour la résolution de problèmes.','s1',6,3,'M113',26,16,10,10,0),(90,'Architecture d\'entreprise et transformation digita','Ce module, intitulé \'Architecture d\'entreprise et transformation digitale\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il se concentre sur la planification et la mise en œuvre de la transformation numérique au sein des organisations.','s1',6,3,'M114',26,6,20,10,0),(91,'Architecture des ordinateurs et systèmes d’exploit','Ce module, intitulé \'Architecture des ordinateurs et systèmes d’exploitation\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il étudie les principes fondamentaux de l\'architecture matérielle et logicielle des ordinateurs.','s1',6,3,'M115',26,10,16,10,0),(92,'Langues Etrangéres (Anglais)','Ce module, intitulé \'Langues Etrangéres (Anglais)\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à développer les compétences linguistiques en anglais pour la communication professionnelle.','s1',3,3,'M116.1',20,6,3,0,15),(93,'Langues Etrangéres (Français)','Ce module, intitulé \'Langues Etrangéres (Français)\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à développer les compétences linguistiques en français pour la communication professionnelle.','s1',3,3,'M116.2',20,6,3,0,15),(94,'Compétences en culture et en art','Ce module, intitulé \'Compétences en culture et en art\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à développer des aptitudes créatives et une ouverture culturelle.','s1',4,3,'M117',26,10,0,8,0),(95,'Programmation Orientée Objet Java','Ce module, intitulé \'Programmation Orientée Objet Java\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il enseigne les principes de la programmation orientée objet en utilisant le langage Java.','s2',6,3,'M122',24,10,30,10,0),(96,'Programmation Python / Programmation fonctionnelle','Ce module, intitulé \'Programmation Python / Programmation fonctionnelle\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore la programmation en Python et les paradigmes de programmation fonctionnelle.','s2',6,3,'M127',36,0,28,10,0),(97,'Développement Web','Ce module, intitulé \'Développement Web\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les technologies et les pratiques de développement d\'applications web modernes.','s2',6,3,'M121',24,0,40,10,0),(98,'Gestion de projets informatiques','Ce module, intitulé \'Gestion de projets informatiques\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il aborde les méthodologies et les outils de gestion de projets dans le domaine informatique.','s2',6,3,'M125',24,10,30,10,0),(99,'Industrie de numérique','Ce module, intitulé \'Industrie de numérique\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il offre une vue d\'ensemble des enjeux et des acteurs de l\'industrie numérique.','s2',6,3,'M123',24,20,20,10,0),(100,'Langues Etrangéres (Anglais)','Ce module, intitulé \'Langues Etrangéres (Anglais)\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à approfondir les compétences linguistiques en anglais pour des contextes technologiques.','s2',3,3,'M124.1',20,6,3,0,16),(101,'Langues Etrangéres (Français)','Ce module, intitulé \'Langues Etrangéres (Français)\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à approfondir les compétences linguistiques en français pour des contextes technologiques.','s2',3,3,'M124.2',20,6,3,0,16),(102,'Cloud Computing','Ce module, intitulé \'Cloud Computing\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les concepts, les modèles de déploiement et les services offerts par le cloud computing.','s3',6,3,'M234',24,10,30,10,0),(103,'Cartographie des systèmes d\'information','Ce module, intitulé \'Cartographie des systèmes d\'information\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il enseigne les techniques de modélisation et de représentation des systèmes d\'information complexes.','s3',6,3,'M232',24,20,20,10,0),(104,'Bases de l\'Intelligence Artificielle','Ce module, intitulé \'Bases de l\'Intelligence Artificielle\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il introduit les fondements théoriques et pratiques de l\'intelligence artificielle.','s3',6,3,'M235',24,10,30,10,0),(105,'Architecture logiciel et UML','Ce module, intitulé \'Architecture logiciel et UML\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore les principes de l\'architecture logicielle et l\'utilisation du langage UML pour la modélisation.','s3',6,3,'M236',24,10,30,10,0),(106,'Communication Professionnelle et Soft Skills-2','Ce module, intitulé \'Communication Professionnelle et Soft Skills-2\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il renforce les compétences en communication et les aptitudes interpersonnelles.','s3',6,3,'M233',30,30,0,0,0),(107,'Gestion de projets digitaux','Ce module, intitulé \'Gestion de projets digitaux\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il se concentre sur la gestion efficace des projets dans l\'environnement numérique.','s3',6,3,'M231',24,30,10,10,0),(108,'Applications de l\'Intelligence Artificielle','Ce module, intitulé \'Applications de l\'Intelligence Artificielle\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il présente des cas d\'utilisation concrets et des implémentations de l\'IA dans divers domaines.','s4',6,3,'M243',24,10,30,10,0),(109,'Ingestion et stockage de données','Ce module, intitulé \'Ingestion et stockage de données\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les techniques d\'acquisition, de transformation et de stockage des données massives.','s4',6,3,'M245',24,10,30,10,0),(110,'Big Data','Ce module, intitulé \'Big Data\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore les concepts, les technologies et les architectures liées aux grandes quantités de données.','s4',6,3,'M242',24,10,30,10,0),(111,'Droit et sécurité des données','Ce module, intitulé \'Droit et sécurité des données\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il aborde les aspects légaux et les mesures de sécurité pour la protection des données numériques.','s4',6,3,'M244',24,20,20,10,0),(112,'Cyber Security','Ce module, intitulé \'Cyber Security\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les principes et les pratiques de la cybersécurité pour protéger les systèmes et les informations.','s4',6,3,'M241',24,10,30,10,0),(113,'Entreprenariat','Ce module, intitulé \'Entreprenariat\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il développe les compétences nécessaires à la création et à la gestion d\'entreprise.','s4',6,3,'M246',24,20,10,20,0),(114,'La veille Stratégique, Scientifique et Technologiq','Ce module, intitulé \'La veille Stratégique, Scientifique et Technologique\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il enseigne les méthodes de surveillance et d\'analyse de l\'environnement stratégique.','s5',6,3,'M351',24,10,30,10,0),(115,'Gouvernance et Urbanisation des SI','Ce module, intitulé \'Gouvernance et Urbanisation des SI\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il couvre les principes de la gouvernance des systèmes d\'information et de leur urbanisation.','s5',6,3,'M353',24,10,30,10,0),(116,'DevOps','Ce module, intitulé \'DevOps\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il introduit les pratiques et les outils de DevOps pour l\'intégration et le déploiement continus.','s5',6,3,'M352',24,10,30,10,0),(117,'Innovation Engineering & Digitalisation','Ce module, intitulé \'Innovation Engineering & Digitalisation\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore les processus d\'ingénierie de l\'innovation et l\'impact de la digitalisation.','s5',6,3,'M355',24,10,30,10,0),(118,'Web Marketing et CRM','Ce module, intitulé \'Web Marketing et CRM\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il aborde les stratégies de marketing digital et la gestion de la relation client.','s5',6,3,'M354',24,10,30,10,0),(119,'Business English','Ce module, intitulé \'Business English\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il vise à améliorer les compétences en anglais des affaires pour le monde professionnel.','s5',6,3,'M356',14,20,0,40,0),(120,'Marketing et management pour les technologies de l','Ce module, intitul3 \'Marketing et management pour les technologies de l\'information\', fait partie du programme de Transformation Digitale et Intelligence Artificielle. Il explore les stratégies de marketing et de gestion appliquées aux TIC.','s2',6,3,'M126',24,30,10,30,0),(121,'Algèbre 1','Ce module, intitulé \'Algèbre 1\', fait partie du programme de l\'Année Préparatoire. Il couvre les concepts fondamentaux de l\'algèbre linéaire et de la théorie des ensembles.','s1',4,4,'M113',26,26,0,0,0),(122,'Analyse 1','Ce module, intitulé \'Analyse 1\', fait partie du programme de l\'Année Préparatoire. Il aborde les bases du calcul différentiel et intégral pour les fonctions d\'une variable.','s1',4,4,'M111',26,26,0,0,0),(123,'Initiation à l’informatique','Ce module, intitulé \'Initiation à l’informatique\', fait partie du programme de l\'Année Préparatoire. Il introduit les concepts fondamentaux de l\'informatique et de la programmation algorithmique.','s1',4,4,'M115',26,26,0,0,0),(124,'Langues Etrangères (Anglais)','Ce module, intitulé \'Langues Etrangères (Anglais)\', fait partie du programme de l\'Année Préparatoire. Il vise à développer les compétences linguistiques en anglais, essentielles pour les études scientifiques.','s1',1,4,'M116.1',7,15,0,0,17),(125,'Langues Etrangères (Français)','Ce module, intitulé \'Langues Etrangères (Français)\', fait partie du programme de l\'Année Préparatoire. Il vise à développer les compétences linguistiques en français, essentielles pour les études scientifiques.','s1',1,4,'M116.2',7,15,0,0,17),(126,'Méthodologie de travail universitaire','Ce module, intitulé \'Méthodologie de travail universitaire\', fait partie du programme de l\'Année Préparatoire. Il enseigne les méthodes et outils nécessaires à la réussite académique à l\'université.','s1',2,4,'M117',28,15,0,0,0),(127,'Algèbre 2','Ce module, intitulé \'Algèbre 2\', fait partie du programme de l\'Année Préparatoire. Il approfondit les notions d\'algèbre linéaire et introduit de nouvelles structures algébriques.','s2',4,4,'M126',26,26,0,0,0),(128,'Analyse 2','Ce module, intitulé \'Analyse 2\', fait partie du programme de l\'Année Préparatoire. Il étend les concepts de l\'analyse aux fonctions de plusieurs variables et aux équations différentielles.','s2',4,4,'M125',26,26,0,0,0),(129,'Programmation C','Ce module, intitulé \'Programmation C\', fait partie du programme de l\'Année Préparatoire. Il enseigne les bases de la programmation structurée en langage C.','s2',4,4,'M123',26,0,26,0,0),(130,'Langues Etrangères (Anglais - S2)','Ce module, intitulé \'Langues Etrangères (Anglais - S2)\', fait partie du programme de l\'Année Préparatoire. Il consolide les compétences en anglais dans un contexte scientifique.','s2',1,4,'M128.1',7,15,0,0,18),(131,'Langues Etrangères (Français - S2)','Ce module, intitulé \'Langues Etrangères (Français - S2)\', fait partie du programme de l\'Année Préparatoire. Il consolide les compétences en français dans un contexte scientifique.','s2',1,4,'M128.2',7,15,0,0,18),(132,'Langues Etrangères (Anglais - S2 Autre)','Ce module, intitulé \'Langues Etrangères (Anglais - S2 Autre)\', fait partie du programme de l\'Année Préparatoire. Il offre une pratique supplémentaire des langues étrangères.','s2',1,4,'M124.1',7,15,0,0,19),(133,'Langues Etrangères (Français - S2 Autre)','Ce module, intitulé \'Langues Etrangères (Français - S2 Autre)\', fait partie du programme de l\'Année Préparatoire. Il offre une pratique supplémentaire des langues étrangères.','s2',1,4,'M124.2',7,15,0,0,19),(134,'Culture digitale','Ce module, intitulé \'Culture digitale\', fait partie du programme de l\'Année Préparatoire. Il explore les concepts et l\'impact de la culture numérique dans la société actuelle.','s2',5,4,'M127',10,0,30,0,0),(135,'Algèbre 3 : Algèbre Quadratique','Ce module, intitulé \'Algèbre 3 : Algèbre Quadratique\', fait partie du programme de l\'Année Préparatoire. Il se penche sur l\'algèbre quadratique et ses applications en géométrie.','s3',6,4,'AP31',32,32,0,0,0),(136,'Analyse 3 : Fonctions de Plusieurs Variables (FPV)','Ce module, intitulé \'Analyse 3 : Fonctions de Plusieurs Variables (FPV)\', fait partie du programme de l\'Année Préparatoire. Il traite du calcul différentiel et intégral pour les fonctions à plusieurs variables.','s3',6,4,'AP32',32,32,0,0,0),(137,'Informatique 2 : Programmation en C','Ce module, intitulé \'Informatique 2 : Programmation en C\', fait partie du programme de l\'Année Préparatoire. Il approfondit la programmation en langage C avec des structures de données plus complexes.','s3',6,4,'AP35',32,32,0,0,0),(138,'Langues et Communication 3 -FR','Ce module, intitulé \'Langues et Communication 3 -FR\', fait partie du programme de l\'Année Préparatoire. Il développe les compétences en communication écrite et orale en français.','s3',2,4,'AP36.1',14,8,0,0,20),(139,'Langues et Communication 3 -Ang','Ce module, intitulé \'Langues et Communication 3 -Ang\', fait partie du programme de l\'Année Préparatoire. Il développe les compétences en communication écrite et orale en anglais.','s3',2,4,'AP36.2',14,8,0,0,20),(140,'Mathématiques Appliquées- Proba et stat descr','Ce module, intitulé \'Mathématiques Appliquées- Proba et stat descr\', fait partie du programme de l\'Année Préparatoire. Il introduit les concepts de probabilités et de statistique descriptive.','s4',3,4,'AP41.1',16,16,0,0,21),(141,'Mathématiques Appliquées- Analyse Numerique','Ce module, intitulé \'Mathématiques Appliquées- Analyse Numerique\', fait partie du programme de l\'Année Préparatoire. Il explore les méthodes numériques pour la résolution de problèmes mathématiques.','s4',3,4,'AP41.2',16,16,0,0,21),(142,'Analyse 4 : Intégrales et Formes Différentielles','Ce module, intitulé \'Analyse 4 : Intégrales et Formes Différentielles\', fait partie du programme de l\'Année Préparatoire. Il couvre les intégrales multiples et les formes différentielles en calcul vectoriel.','s4',6,4,'AP42',32,32,0,0,0),(143,'Informatique 3 : Outils Informatique','Ce module, intitulé \'Informatique 3 : Outils Informatique\', fait partie du programme de l\'Année Préparatoire. Il présente divers outils informatiques essentiels pour l\'ingénierie et la science.','s4',6,4,'AP45',32,32,0,0,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,22,'2025-06-11 22:16:45','Bienvenue sur E-service',NULL,'unread','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.'),(2,24,'2025-06-11 22:18:13','Bienvenue sur E-service',NULL,'read','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.'),(3,25,'2025-06-11 22:18:57','Bienvenue sur E-service',NULL,'unread','Veuillez changer votre mot de passe temporaire dès que possible pour la sécurité de votre compte. Vous pouvez le faire en allant dans les paramètres de votre profil.'),(4,24,'2025-06-11 22:29:26','Affectation enregistrée',NULL,'unread','Vos choix de modules ont bien été enregistrés : Architecture des ordinateurs, Langage C avancé et structures de données, Langues Etrangéres (Français), Langues Etrangéres (Anglais). ⚠️ Attention : votre charge horaire (0 h) est inférieure au minimum requis (100 h).');
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
  `id_speciality` int(11) NOT NULL,
  UNIQUE KEY `id_professor` (`id_professor`),
  KEY `id_deparetement` (`id_deparetement`),
  KEY `id_speciality` (`id_speciality`),
  CONSTRAINT `professor_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`),
  CONSTRAINT `professor_ibfk_2` FOREIGN KEY (`id_professor`) REFERENCES `user` (`id_user`),
  CONSTRAINT `professor_ibfk_3` FOREIGN KEY (`id_speciality`) REFERENCES `speciality` (`id_speciality`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor`
--

LOCK TABLES `professor` WRITE;
/*!40000 ALTER TABLE `professor` DISABLE KEYS */;
INSERT INTO `professor` VALUES (22,300,100,'coordonnateur',1,25),(24,250,100,'chef_deparetement',1,26),(25,350,100,'normal',1,23);
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `speciality`
--

LOCK TABLES `speciality` WRITE;
/*!40000 ALTER TABLE `speciality` DISABLE KEYS */;
INSERT INTO `speciality` VALUES (1,'Informatique Fondamentale',1),(2,'Intelligence Artificielle',1),(3,'Développement Logiciel',1),(4,'Réseaux et Sécurité',1),(5,'Bases de Données',1),(6,'Algorithmique Avancée',1),(7,'Statistiques et Optimisation',1),(8,'Théorie des Graphes',1),(9,'MI/Français',1),(10,'MI/Anglais',1),(11,'Structure et Matériaux',2),(12,'Hydraulique et Environnement',2),(13,'Géotechnique',2),(14,'Urbanisme et Transport',2),(15,'Énergies Renouvelables',2),(16,'Efficacité Énergétique',2),(17,'Réseaux Électriques Intelligents',2),(18,'Gestion de Projets BTP',2),(19,'Pollution et Traitement des Déchets',2),(20,'GCEE/Français',2),(21,'GCEE/Anglais',2),(22,'Mathématiques Approfondies',3),(23,'Physique Fondamentale',3),(24,'Chimie Générale',3),(25,'Mécanique des Fluides',3),(26,'Algèbre Linéaire',3),(27,'Analyse Numérique',3),(28,'AP/Français',3),(29,'AP/Anglais',3);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'ADMIN','ENSAH','default.webp','rc12435','adminEnsah@eservice.com','admin','$2y$10$CWflyVgtOuJjEvH5.BHuAeI8kCNhJGZJ1OM4pKy.C0Pg9BY3y1h/6','0653646266','ensah  alhoceima','2000-01-01','2025-03-03 00:00:00','active'),(22,'Moad','Abdou','default.webp','RC12345','moad@gmail.com','professor','$2y$10$ddMd26yHKtqyhqjIt07ZbeRfMLBPwmWOEESoP73TiHtnvfUwE.xGC','612345678','Targuist Hoceima','2001-06-05','2025-06-11 22:16:45','active'),(24,'Hassan','Hassan','default.webp','RC12340','hassan@gmail.com','professor','$2y$10$ddMd26yHKtqyhqjIt07ZbeRfMLBPwmWOEESoP73TiHtnvfUwE.xGC','612345678','Targuist Hoceima','2001-06-05','2025-06-11 22:18:13','active'),(25,'ayoube','ayoube','default.webp','RC12000','ayoube@gmail.com','professor','$2y$10$ddMd26yHKtqyhqjIt07ZbeRfMLBPwmWOEESoP73TiHtnvfUwE.xGC','612345600','Targuist bni boayach','2001-06-05','2025-06-11 22:18:57','active');
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

-- Dump completed on 2025-06-11 22:32:33
