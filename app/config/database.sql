-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Exportiere Struktur von Tabelle enxvisuals.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index` int(11) NOT NULL DEFAULT 0,
  `showAlways` enum('true','false') NOT NULL DEFAULT 'false',
  `hideInHeader` enum('true','false') NOT NULL DEFAULT 'false',
  `hideInFooter` enum('true','false') NOT NULL DEFAULT 'false',
  `mustBeLoggedIn` enum('true','false','both') NOT NULL DEFAULT 'both',
  `isRawPage` enum('true','false') NOT NULL DEFAULT 'false',
  `active` enum('true','false') NOT NULL DEFAULT 'true',
  `showPreloader` enum('true','false') NOT NULL DEFAULT 'false',
  `category` enum('normal','info') NOT NULL DEFAULT 'normal',
  `color` varchar(10) NOT NULL DEFAULT '#9899ac',
  `icon` varchar(100) NOT NULL DEFAULT 'circle',
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `headline` varchar(100) NOT NULL,
  `subline` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `index` (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle enxvisuals.pages: ~2 rows (ungefähr)
DELETE FROM `pages`;
INSERT INTO `pages` (`id`, `index`, `showAlways`, `hideInHeader`, `hideInFooter`, `mustBeLoggedIn`, `isRawPage`, `active`, `showPreloader`, `category`, `color`, `icon`, `name`, `title`, `headline`, `subline`) VALUES
	(1, 1, 'false', 'false', 'false', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'index', 'Home', '', ''),
	(2, 100, 'false', 'true', 'true', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'error', 'Error', '', '');

-- Exportiere Struktur von Tabelle enxvisuals.translations
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(1000) NOT NULL,
  `category` varchar(100) NOT NULL,
  `de` varchar(1000) NOT NULL,
  `en` varchar(1000) NOT NULL,
  `active` enum('true','false') NOT NULL DEFAULT 'true',
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle enxvisuals.translations: ~0 rows (ungefähr)
DELETE FROM `translations`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
