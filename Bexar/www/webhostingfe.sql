-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Ven 10 Janvier 2020 à 02:57
-- Version du serveur :  10.1.41-MariaDB-0+deb9u1
-- Version de PHP :  7.0.33-14+0~20191218.25+debian9~1.gbpae1889

--    This file is part of Web Hosting FE Light.
--
--    Web Hosting FE Light is free software: you can redistribute it and/or modify
--    it under the terms of the GNU General Public License as published by
--    the Free Software Foundation, either version 3 of the License, or
--    (at your option) any later version.
--
--    Web Hosting FE Light is distributed in the hope that it will be useful,
--    but WITHOUT ANY WARRANTY; without even the implied warranty of
--    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
--    GNU General Public License for more details.
--
--    You should have received a copy of the GNU General Public License
--    along with Web Hosting FE Light.  If not, see <https://www.gnu.org/licenses/>.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `webhostingfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `bindextension`
--

CREATE TABLE `bindextension` (
  `id` int(25) NOT NULL,
  `extension` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `bindextension`
--

INSERT INTO `bindextension` (`id`, `extension`) VALUES
(1, '.be'),
(2, '.biz'),
(3, '.com'),
(4, '.eu'),
(5, '.fr'),
(6, '.info'),
(7, '.net'),
(8, '.org');

-- --------------------------------------------------------

--
-- Structure de la table `bindrecord`
--

CREATE TABLE `bindrecord` (
  `id` int(25) NOT NULL,
  `name` text,
  `domaine` text,
  `record` text,
  `type` text,
  `target` text,
  `readonly` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `bindzone`
--

CREATE TABLE `bindzone` (
  `id` int(25) NOT NULL,
  `domaine` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `certbot`
--

CREATE TABLE `certbot` (
  `id` int(25) NOT NULL,
  `sftp` int(25) DEFAULT NULL,
  `state` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mysql`
--

CREATE TABLE `mysql` (
  `id` int(25) NOT NULL,
  `databasename` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sftp`
--

CREATE TABLE `sftp` (
  `id` int(25) NOT NULL,
  `fqdn` text,
  `loglevel` text,
  `phpvers` text,
  `records` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(25) NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` text CHARACTER SET utf8,
  `password` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `bindextension`
--
ALTER TABLE `bindextension`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bindrecord`
--
ALTER TABLE `bindrecord`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bindzone`
--
ALTER TABLE `bindzone`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `certbot`
--
ALTER TABLE `certbot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mysql`
--
ALTER TABLE `mysql`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sftp`
--
ALTER TABLE `sftp`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `bindextension`
--
ALTER TABLE `bindextension`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `bindrecord`
--
ALTER TABLE `bindrecord`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bindzone`
--
ALTER TABLE `bindzone`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `certbot`
--
ALTER TABLE `certbot`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `mysql`
--
ALTER TABLE `mysql`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `sftp`
--
ALTER TABLE `sftp`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
