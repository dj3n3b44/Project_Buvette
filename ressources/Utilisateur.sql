-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+focal2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 23 jan. 2026 à 10:08
-- Version du serveur : 8.0.42-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dutinfopw201639`
--

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `id_user` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `solde` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mdp`, `solde`) VALUES
(1, 'Barman', 'Super', 'barman@asso.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 0.00),
(2, 'Sorana', 'Anton', 'anton@test.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 60.40),
(3, 'Etudiant', 'Lambda', 'test@test.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 913.30),
(4, 'Boss', 'Hugo', 'gestion@asso.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 100.00),
(5, 'Gestion', 'BDE', 'gestion@bde.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 0.00),
(6, 'Dieu', 'Admin', 'admin@asso.fr', '$2y$10$E0J26TNtR6LKTTuGnL9dLexOqT6gpKYZ/BPu0K8y.qzhXhjXrF3Qm', 0.00),
(7, 'Jordan', 'Lina', 'barman2@asso.fr', '$2y$10$oaZ0fu.9S0RwkuQA76wvROZVBAPUUEZlekafHlDGpgBczkyulO1Oq', 0.00),
(8, 'test', 'hehe', 'test3@asso.fr', '$2y$10$dcugO5iJu91LxHrCYhZue.LkszzyJ4vvK8oUGDrGs8IzaEEXx2YeW', 0.00);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
