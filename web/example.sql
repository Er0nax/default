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

-- Exportiere Struktur von Tabelle default.api_tokens
DROP TABLE IF EXISTS `api_tokens`;
CREATE TABLE IF NOT EXISTS `api_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8 NOT NULL,
  `token` varchar(30) NOT NULL,
  `uses` int(11) NOT NULL DEFAULT 0,
  `active` enum('true','false') NOT NULL DEFAULT 'true',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle default.api_tokens: ~1 rows (ungefähr)
DELETE FROM `api_tokens`;
INSERT INTO `api_tokens` (`id`, `ip`, `token`, `uses`, `active`, `created_at`, `updated_at`) VALUES
	(1, '127.0.0.1', 'ipeuz9GLH2mKwid7OD81HEopwQaKy9', 0, 'true', '2024-06-17 11:50:12', '2024-06-17 11:50:12');

-- Exportiere Struktur von Tabelle default.api_whitelist
DROP TABLE IF EXISTS `api_whitelist`;
CREATE TABLE IF NOT EXISTS `api_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `active` enum('true','false') NOT NULL DEFAULT 'true',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle default.api_whitelist: ~1 rows (ungefähr)
DELETE FROM `api_whitelist`;
INSERT INTO `api_whitelist` (`id`, `ip`, `active`, `updated_at`, `created_at`) VALUES
	(1, '127.0.0.1', 'true', '2024-06-17 11:50:00', '2024-06-17 11:50:00');

-- Exportiere Struktur von Tabelle default.pages
DROP TABLE IF EXISTS `pages`;
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
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `index` (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle default.pages: ~4 rows (ungefähr)
DELETE FROM `pages`;
INSERT INTO `pages` (`id`, `index`, `showAlways`, `hideInHeader`, `hideInFooter`, `mustBeLoggedIn`, `isRawPage`, `active`, `showPreloader`, `category`, `color`, `icon`, `name`, `title`, `headline`, `subline`, `updatedAt`, `createdAt`) VALUES
	(1, 1, 'false', 'false', 'false', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'index', 'Home', '', '', '2024-06-17 11:49:14', '2024-06-17 11:49:14'),
	(2, 99, 'false', 'true', 'true', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'error', 'Error', '', '', '2024-06-17 15:03:37', '2024-06-17 11:49:19'),
	(3, 10, 'false', 'false', 'true', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'about', 'About', '', '', '2024-06-17 14:51:28', '2024-06-17 14:40:49'),
	(4, 90, 'false', 'true', 'false', 'both', 'false', 'true', 'false', 'normal', '#9899ac', 'circle', 'policy', 'Policy', '', '', '2024-06-17 14:55:51', '2024-06-17 14:55:46');

-- Exportiere Struktur von Tabelle default.translations
DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL DEFAULT 'site',
  `value` varchar(2000) NOT NULL DEFAULT '',
  `de` varchar(2000) DEFAULT '',
  `en` varchar(2000) DEFAULT '',
  `active` enum('true','false') NOT NULL DEFAULT 'true',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle default.translations: ~0 rows (ungefähr)
DELETE FROM `translations`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
