-- Adminer 4.8.1 MySQL 5.5.5-10.10.2-MariaDB-1:10.10.2+maria~ubu2204 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id_comment` varchar(36) NOT NULL,
  `comment` longtext DEFAULT NULL,
  `id_event` varchar(36) DEFAULT NULL,
  `id_user` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id_comment`),
  KEY `id_evenement_idx` (`id_event`),
  KEY `id_utilisateur_idx` (`id_user`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id_event` varchar(36) NOT NULL,
  `title` varchar(80) NOT NULL,
  `description` longtext DEFAULT NULL,
  `date` timestamp NOT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `code_share` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `event_event`;
CREATE TABLE `event_event` (
  `title` varchar(80) DEFAULT NULL,
  `id_main_event` varchar(36) DEFAULT NULL,
  `id_additional_event` varchar(36) DEFAULT NULL,
  KEY `id_main_event_idx` (`id_main_event`),
  KEY `id_additional_event_idx` (`id_additional_event`),
  CONSTRAINT `event_event_ibfk_1` FOREIGN KEY (`id_additional_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_event_ibfk_2` FOREIGN KEY (`id_main_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `event_user`;
CREATE TABLE `event_user` (
  `is_organisator` tinyint(1) NOT NULL,
  `state` varchar(30) NOT NULL,
  `is_here` tinyint(1) NOT NULL,
  `comment` longtext DEFAULT NULL,
  `id_event` varchar(36) DEFAULT NULL,
  `id_user` varchar(36) DEFAULT NULL,
  KEY `id_event_idx` (`id_event`),
  KEY `id_user_idx` (`id_user`),
  CONSTRAINT `event_user_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_user_ibfk_2` FOREIGN KEY (`id_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `id_link` varchar(36) NOT NULL,
  `title` varchar(80) DEFAULT NULL,
  `link` varchar(750) DEFAULT NULL,
  `id_event` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id_link`),
  KEY `id_evenement_idx` (`id_event`),
  CONSTRAINT `link_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id_location` varchar(36) NOT NULL,
  `name` varchar(80) DEFAULT NULL,
  `lat` decimal(65,0) DEFAULT NULL,
  `long` decimal(65,0) DEFAULT NULL,
  `is_related` tinyint(1) DEFAULT NULL,
  `id_event` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id_location`),
  KEY `id_evenement_idx` (`id_event`),
  CONSTRAINT `location_ibfk_1` FOREIGN KEY (`id_event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id_media` varchar(36) NOT NULL,
  `path` varchar(500) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `id_comment` varchar(36) NOT NULL,
  PRIMARY KEY (`id_media`),
  KEY `id_comment_idx` (`id_comment`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`id_comment`) REFERENCES `comment` (`id_comment`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` varchar(36) NOT NULL,
  `email` varchar(50) NOT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `firstname` varchar(30) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `mail_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


-- 2023-04-03 09:54:16
