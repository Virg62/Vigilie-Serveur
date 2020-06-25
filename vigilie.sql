-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Jeu 25 Juin 2020 à 21:02
-- Version du serveur :  10.1.44-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-29+0~20200514.36+debian9~1.gbp126f6f

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `carry`
--

-- --------------------------------------------------------

--
-- Structure de la table `alerts`
--

CREATE TABLE `alerts` (
  `id_alert` int(11) NOT NULL COMMENT 'Identifiant Alerte',
  `user_id` int(11) NOT NULL COMMENT 'Utilisateur ayant créé alerte',
  `title` varchar(255) DEFAULT NULL COMMENT 'Titre de l''alerte',
  `content` text NOT NULL COMMENT 'Contenu de l''alerte',
  `type_alert` int(11) NOT NULL DEFAULT '3' COMMENT 'Type d''alerte',
  `pic` varchar(255) DEFAULT NULL COMMENT 'Lien photo jointe',
  `location` varchar(255) DEFAULT NULL COMMENT 'Localisation de l''alerte',
  `date` varchar(255) NOT NULL DEFAULT 'NOW()' COMMENT 'date alerte',
  `validated` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Si validée ou pas'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `type_alert`
--

CREATE TABLE `type_alert` (
  `id_type` int(11) NOT NULL,
  `label` varchar(255) NOT NULL COMMENT 'Label de l''alerte',
  `notify` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `type_alert`
--

INSERT INTO `type_alert` (`id_type`, `label`, `notify`) VALUES
(1, 'Urgence', 1),
(2, 'Info Cantonniers', 0),
(3, 'Non Classé', 0),
(4, 'Info conseil municipal', 0),
(5, 'Information', 0);

-- --------------------------------------------------------

--
-- Structure de la table `type_user`
--

CREATE TABLE `type_user` (
  `id_type` int(11) NOT NULL COMMENT 'ident type utilisateur',
  `label` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `type_user`
--

INSERT INTO `type_user` (`id_type`, `label`) VALUES
(1, 'citoyen'),
(2, 'élu');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL COMMENT 'Identifiant Utilisateur',
  `username` varchar(100) NOT NULL COMMENT 'Pseudonyme de l''utilisateur',
  `lastname` varchar(255) NOT NULL COMMENT 'Nom de l''utilisateur',
  `firstname` varchar(255) NOT NULL COMMENT 'Prénom de l''utilisateur',
  `pse` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'premiers seccours',
  `mail` varchar(255) NOT NULL COMMENT 'Adresse mail de l''utilisateur',
  `address` varchar(255) NOT NULL COMMENT 'Adresse physique de l''utilisateur',
  `password` varchar(255) NOT NULL COMMENT 'Mot de passe de l''utilisateur',
  `type_user` int(11) NOT NULL DEFAULT '1' COMMENT 'type d''utilisateur',
  `valide` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id_user`, `username`, `lastname`, `firstname`, `pse`, `mail`, `address`, `password`, `type_user`, `valide`) VALUES
(1, 'root', 'Root', 'User', 0, 'root@localhost', 'Dans votre commune', '$2y$10$JGAXRnsNJdRXFZHNr/oOQ.dQLO.gz92Rewdh/XQKbFnlt9FO35TIS', 2, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id_alert`);

--
-- Index pour la table `type_alert`
--
ALTER TABLE `type_alert`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `type_user`
--
ALTER TABLE `type_user`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id_alert` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant Alerte', AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `type_alert`
--
ALTER TABLE `type_alert`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `type_user`
--
ALTER TABLE `type_user`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ident type utilisateur', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant Utilisateur', AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
