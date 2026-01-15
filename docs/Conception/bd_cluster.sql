-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 12 fév. 2025 à 10:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_cluster`
--

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE logs (
  log_id int(11) NOT NULL AUTO_INCREMENT,
  ip_address varchar(50) NOT NULL,
  login varchar(50) DEFAULT NULL,
  date datetime NOT NULL,
  action varchar(50) NOT NULL,
  PRIMARY KEY (log_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password`
--

CREATE TABLE password (
  user_id int(11) NOT NULL,
  password varchar(40) NOT NULL,
  PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `password`
--

INSERT INTO password (user_id, password) VALUES
(1, 'A82E39E70454BB90'),
(2, 'A82E39E70450A781');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT,
  login varchar(32) NOT NULL,
  profil_picture BLOB,
  PRIMARY KEY (id),
  UNIQUE (login)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO user (id, login) VALUES
(1, 'adminsys'),
(2, 'adminweb');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `password`
--
ALTER TABLE password
  ADD CONSTRAINT fk_user_id_password FOREIGN KEY (user_id) REFERENCES user (id);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
