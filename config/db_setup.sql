-- Active: 1730824903744@@127.0.0.1@3306@eservice

CREATE DATABASE eservice;

USE  eservice;


CREATE TABLE IF NOT EXISTS deparetement(
    id_deparetement INT  PRIMARY KEY AUTO_INCREMENT,
    title CHAR(50) UNIQUE NOT NULL ,
    description VARCHAR(400) NOT NULL
);


CREATE TABLE IF NOT EXISTS filiere(
    id_filiere INT  PRIMARY KEY AUTO_INCREMENT,
    title CHAR(50) UNIQUE NOT NULL ,
    description VARCHAR(400) NOT NULL,
    id_deparetement INT NOT NULL, 
    FOREIGN KEY(id_deparetement) REFERENCES deparetement(id_deparetement)
);

CREATE TABLE IF NOT EXISTS module(
    id_module INT  PRIMARY KEY AUTO_INCREMENT,
    title CHAR(50) UNIQUE NOT NULL ,
    description VARCHAR(400) NOT NULL,
    volume_horaire INT  NOT NULL,
    semester ENUM('s1','s2','s3','s4','s5','s6'),
    credits INT NOT  NULL,
    id_filiere INT NOT NULL, 
    FOREIGN KEY(id_filiere) REFERENCES filiere(id_filiere)
);


CREATE TABLE IF NOT EXISTS user(
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    firstName CHAR(30) NOT NULL ,
    lastName CHAR(30) NOT NULL ,
    email CHAR(30) NOT NULL ,
    role ENUM('professor','vacataire','admin') NOT NULL,
    password CHAR(255) NOT  NULL,
    phone INT NOT NULL, 
    birth_date DATE NOT  NULL
);

CREATE TABLE IF NOT EXISTS admin(
    id_admin INT UNIQUE NOT NULL,
    Foreign Key (id_admin) REFERENCES user(id_user)
);


CREATE TABLE IF NOT EXISTS professor(
    id_professor INT UNIQUE NOT NULL,
    max_hours INT NOT NULL,
    min_hours INT NOT NULL,
    specialite CHAR(30) NOT NULL,
    role ENUM('chef_deparetement','coordonnateur'), 
    Foreign Key (id_professor) REFERENCES user(id_user)
);

CREATE TABLE IF NOT EXISTS coordonnateur(
    id_coordonnateur INT UNIQUE NOT NULL,
    id_filiere INT NOT NULL,
    Foreign Key (id_coordonnateur) REFERENCES professor(id_professor),
    Foreign Key (id_filiere) REFERENCES filiere(id_filiere)
);

CREATE TABLE IF NOT EXISTS chef_deparetement(
    id_chef_deparetement INT UNIQUE NOT NULL,
    id_deparetement INT NOT NULL,
    Foreign Key (id_chef_deparetement) REFERENCES professor(id_professor),
    Foreign Key (id_deparetement) REFERENCES deparetement(id_deparetement)
);


CREATE TABLE IF NOT EXISTS coordonnateur(
    id_coordonnateur INT UNIQUE NOT NULL,
    id_filiere INT NOT NULL,
    Foreign Key (id_coordonnateur) REFERENCES user(id_user),
    Foreign Key (id_filiere) REFERENCES filiere(id_filiere)
);

CREATE TABLE IF NOT EXISTS vacataire(
    id_vacataire INT UNIQUE NOT NULL,
    id_coordonnateur INT NOT NULL, 
    specialite CHAR(30) NOT NULL,
    Foreign Key (id_vacataire) REFERENCES user(id_user),
    Foreign Key (id_coordonnateur) REFERENCES coordonnateur(id_coordonnateur)
);

CREATE TABLE IF NOT EXISTS affectation_vacataire(
    to_vacataire INT NOT NULL,
    by_coordonnateur INT NOT NULL, 
    id_module INT NOT NULL,
    annee YEAR NOT NULL,
    Foreign Key (to_vacataire) REFERENCES vacataire(id_vacataire),
    Foreign Key (by_coordonnateur) REFERENCES coordonnateur(id_coordonnateur),
    Foreign Key (id_module) REFERENCES module(id_module)
);


CREATE TABLE IF NOT EXISTS affectation_professor(
    to_professor INT NOT NULL,
    by_chef_deparetement INT NOT NULL, 
    id_module INT NOT NULL,
    annee YEAR NOT NULL,
    Foreign Key (to_professor) REFERENCES professor(id_professor),
    Foreign Key (by_chef_deparetement) REFERENCES chef_deparetement(id_chef_deparetement),
    Foreign Key (id_module) REFERENCES module(id_module)
);

CREATE TABLE IF NOT EXISTS choix_module(
    by_professor INT NOT NULL,
    id_module INT NOT NULL,
    date_creation DATE NOT NULL,
    date_reponce DATE,
    status enum( 'validated', 'declined', 'in progress') NOT NULL,
    Foreign Key (by_professor) REFERENCES professor(id_professor),
    Foreign Key (id_module) REFERENCES module(id_module)
);


CREATE TABLE IF NOT EXISTS Notes(
    id_note INT PRIMARY KEY AUTO_INCREMENT,
    id_module INT NOT NULL,
    id_vacataire INT NULL,
    id_professor INT NULL,
    date_upload DATE NOT NULL,
    file_id INT NOT NULL, -- where the notes are stored --
    Foreign Key (id_module) REFERENCES module(id_module),
    Foreign Key (id_professor) REFERENCES professor(id_professor),
    Foreign Key (id_vacataire) REFERENCES vacataire(id_vacataire),
    CHECK (
        (id_professor IS NOT NULL AND id_vacataire IS NULL) OR
        (id_professor IS NULL AND id_vacataire IS NOT NULL)
    )
);


CREATE TABLE IF NOT EXISTS notifications(
    id_notification INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    date_time DATETIME NOT NULL,
    title CHAR(30) UNIQUE NOT NULL ,
    content VARCHAR(400) NOT NULL,
    Foreign Key (id_user) REFERENCES user(id_user)
);

