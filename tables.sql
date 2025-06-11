CREATE TABLE `activities` (
  `id_activite` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `create_time` datetime NOT NULL COMMENT 'Create Time',
  `content` text NOT NULL,
  `icon` varchar(64) NOT NULL,
  PRIMARY KEY (`id_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  UNIQUE KEY `id_admin` (`id_admin`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `announces` (
  `id_announce` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `create_time` datetime NOT NULL COMMENT 'Create Time',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `id_admin` int(11) NOT NULL,
  PRIMARY KEY (`id_announce`),
  CONSTRAINT `announces_ibfk_1` FOREIGN KEY (`id_announce`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `chef_deparetement` (
  `id_chef_deparetement` int(11) NOT NULL,
  UNIQUE KEY `id_chef_deparetement` (`id_chef_deparetement`),
  CONSTRAINT `chef_deparetement_ibfk_1` FOREIGN KEY (`id_chef_deparetement`) REFERENCES `professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `coordonnateur` (
  `id_coordonnateur` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  UNIQUE KEY `id_coordonnateur` (`id_coordonnateur`),
  KEY `id_filiere` (`id_filiere`),
  CONSTRAINT `coordonnateur_ibfk_1` FOREIGN KEY (`id_coordonnateur`) REFERENCES `professor` (`id_professor`),
  CONSTRAINT `coordonnateur_ibfk_2` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id_filiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `deparetement` (
  `id_deparetement` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  PRIMARY KEY (`id_deparetement`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `feature_deadlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feature` enum('choose_modules','upload_notes') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `filiere` (
  `id_filiere` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  `id_deparetement` int(11) NOT NULL,
  PRIMARY KEY (`id_filiere`),
  UNIQUE KEY `title` (`title`),
  KEY `id_deparetement` (`id_deparetement`),
  CONSTRAINT `filiere_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `module` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `description` varchar(400) NOT NULL,
  `semester` enum('s1','s2','s3','s4','s5','s6') DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL DEFAULT 1,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `speciality` (
  `id_speciality` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) NOT NULL,
  `id_deparetement` int(11) NOT NULL,
  PRIMARY KEY (`id_speciality`),
  KEY `id_deparetement` (`id_deparetement`),
  CONSTRAINT `speciality_ibfk_1` FOREIGN KEY (`id_deparetement`) REFERENCES `deparetement` (`id_deparetement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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