-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 25 nov. 2024 à 18:21
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
-- Base de données : `reservation_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `num_employe` bigint(20) NOT NULL,
  `departement` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `role` enum('ADMIN','USER') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`num_employe`, `departement`, `email`, `nom`, `password`, `prenom`, `role`) VALUES
(3, 'hr', 'test@gmail.com', 'test', '$2y$10$sSbdeft5I4/tkyIrLxMKCeJjICMaukxmG/IC2E0f0YWjuZCYs.gZ.', 'test', 'USER'),
(4, 'SI', 'admin@gmail.com', 'mhammed', '$2y$10$3n.LXchE9qh8a/LW7/hfEOdxcSyhQn7AD8gmN7FaLZ/LUWfztoy6a', 'elkhalfi', 'ADMIN'),
(5, 'HR', 'user@gmail.com', 'ismail', '$2y$10$V/Az1HrxTi6Sqb0th2Ey2.fJwOIozkn4cuGhhtN8qwpaIFbZPnSPy', 'aboulkacim', 'USER'),
(7, 'IT', 'alice@example.com', 'Alice', 'password123', 'Alice', 'USER'),
(8, 'HR', 'bob@example.com', 'Bob', 'password123', 'Bob', 'USER'),
(9, 'IT', 'carol@example.com', 'Carol', 'password123', 'Carol', 'ADMIN');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `date_reservation` date DEFAULT NULL,
  `duree` int(11) NOT NULL,
  `heure_fin` time DEFAULT NULL,
  `heure_debut` time(6) DEFAULT NULL,
  `num_employe` bigint(20) DEFAULT NULL,
  `num_reservation` bigint(20) NOT NULL,
  `num_salle` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`date_reservation`, `duree`, `heure_fin`, `heure_debut`, `num_employe`, `num_reservation`, `num_salle`) VALUES
('2024-11-26', 60, '11:00:00', '10:00:00.000000', 5, 15, 1);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `capacite` int(11) NOT NULL,
  `num_salle` bigint(20) NOT NULL,
  `type_salle` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`capacite`, `num_salle`, `type_salle`) VALUES
(5, 1, 'bureau 1'),
(1, 2, 'bureau 2'),
(100, 5, 'Conférence 1'),
(200, 6, 'Conférence 1'),
(5, 7, 'formation 1'),
(10, 8, 'formation 2');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`num_employe`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`num_reservation`),
  ADD KEY `FKfio6ycycu7lmuotl7hnpi30i4` (`num_employe`),
  ADD KEY `FK2mqtrybe5byykqg65pk3at9b4` (`num_salle`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`num_salle`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `num_employe` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `num_reservation` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `num_salle` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK2mqtrybe5byykqg65pk3at9b4` FOREIGN KEY (`num_salle`) REFERENCES `salle` (`num_salle`),
  ADD CONSTRAINT `FKfio6ycycu7lmuotl7hnpi30i4` FOREIGN KEY (`num_employe`) REFERENCES `employe` (`num_employe`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
