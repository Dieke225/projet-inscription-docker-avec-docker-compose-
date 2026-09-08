-- Création de la base si elle n'existe pas
CREATE DATABASE IF NOT EXISTS inscriptions
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE inscriptions;


-- Création de la table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);