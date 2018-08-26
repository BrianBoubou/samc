-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 26 août 2018 à 19:31
-- Version du serveur :  5.7.21
-- Version de PHP :  5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `soap`
--

-- --------------------------------------------------------

--
-- Structure de la table `days`
--

DROP TABLE IF EXISTS `days`;
CREATE TABLE IF NOT EXISTS `days` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(10) UNSIGNED NOT NULL,
  `day` date NOT NULL,
  `arrived_at` time DEFAULT NULL,
  `leaved_at` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `difference` double NOT NULL DEFAULT '0',
  `excused` int(3) NOT NULL DEFAULT '0',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `days_student_id_foreign` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `edit_pangs`
--

DROP TABLE IF EXISTS `edit_pangs`;
CREATE TABLE IF NOT EXISTS `edit_pangs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(10) UNSIGNED NOT NULL,
  `day` date NOT NULL,
  `quantity` double NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `edit_pangs_student_id_foreign` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_user_id_foreign` (`user_id`),
  KEY `logs_category_id_foreign` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `log_categories`
--

DROP TABLE IF EXISTS `log_categories`;
CREATE TABLE IF NOT EXISTS `log_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `log_categories`
--

INSERT INTO `log_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Check-in / out', '2018-06-19 10:00:00', '2018-06-19 10:00:00'),
(2, 'Edit Check-in / out', '2018-06-19 10:00:00', '2018-06-19 10:00:00'),
(3, 'Ajout d\'excuse', '2018-06-19 10:00:00', '2018-06-19 10:00:00'),
(4, 'Suppression d\'excuse', '2018-06-19 10:00:00', '2018-06-19 10:00:00'),
(5, 'Ajouter / Retirer des pangs', '2018-06-19 10:00:00', '2018-06-19 10:00:00'),
(6, 'Suppression d\'ajout / retrait de pangs', '2018-06-19 10:00:00', '2018-06-19 10:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `pang_settings`
--

DROP TABLE IF EXISTS `pang_settings`;
CREATE TABLE IF NOT EXISTS `pang_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `morning_early` time NOT NULL DEFAULT '08:00:00',
  `morning_start` time NOT NULL DEFAULT '09:00:00',
  `morning_late` time NOT NULL DEFAULT '10:00:00',
  `morning_end` time NOT NULL DEFAULT '13:30:00',
  `afternoon_start` time NOT NULL DEFAULT '14:30:00',
  `afternoon_leave` time NOT NULL DEFAULT '16:30:00',
  `afternoon_extra` time NOT NULL DEFAULT '19:00:00',
  `afternoon_end` time NOT NULL DEFAULT '20:00:00',
  `earning_pang` double NOT NULL DEFAULT '0.3',
  `losing_pang` double NOT NULL DEFAULT '0.5',
  `absent_loss` int(11) NOT NULL DEFAULT '50',
  `current_promo_id` int(11) NOT NULL DEFAULT '1999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pang_settings`
--

INSERT INTO `pang_settings` (`id`, `morning_early`, `morning_start`, `morning_late`, `morning_end`, `afternoon_start`, `afternoon_leave`, `afternoon_extra`, `afternoon_end`, `earning_pang`, `losing_pang`, `absent_loss`, `current_promo_id`, `created_at`, `updated_at`) VALUES
(6, '08:00:00', '10:00:00', '10:00:00', '13:30:00', '14:30:00', '17:40:00', '18:00:00', '20:00:00', 0.3, 0.5, 75, 1, '2018-06-04 10:37:21', '2018-06-04 10:37:21');

-- --------------------------------------------------------

--
-- Structure de la table `promos`
--

DROP TABLE IF EXISTS `promos`;
CREATE TABLE IF NOT EXISTS `promos` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promos`
--

INSERT INTO `promos` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'samc', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `promo_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_promo_id_foreign` (`promo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `promo_id`, `created_at`, `updated_at`, `email`) VALUES
(16, 'alfoussein', 'doucoure', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(18, 'anas', 'belbaz', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(19, 'andry-michael', 'johnson', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(20, 'antonin', 'rieux', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(21, 'arnaud', 'bordack', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(22, 'badis', 'benkhelfallah', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(23, 'baptiste', 'chiocca', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(24, 'baptiste', 'dumont', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(25, 'benjamin', 'termeau', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(26, 'bilel', 'kharbouche', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(27, 'brice', 'contet', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(28, 'cassandra', 'sangkhavongs', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(29, 'christopher', 'nicolas', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(30, 'daphne', 'barres', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(31, 'djamel', 'bouhadjar', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(32, 'djarlane', 'egblomasse', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(33, 'dylan', 'luchmun', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(34, 'egor', 'khaybulov', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(35, 'elie', 'yelfouf', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(36, 'estelle', 'barna', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(37, 'estelle', 'goncalves', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(39, 'florian', 'lemyre', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(40, 'jawn', 'bekrarchouche', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(41, 'joel', 'tan', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(42, 'julien', 'hermine-bromberg', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(44, 'kevan', 'sadeghi', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', 'kevan.sadeghi@epitech.eu'),
(46, 'marley', 'cideron', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(47, 'mathieu', 'kermoal', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(48, 'mehdy', 'lemeurs', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(49, 'modar', 'hmedan', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(50, 'nicolas', 'evina-edimo', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(51, 'nicolas', 'havard', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(52, 'nicolas', 'kayi', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(53, 'nils', 'germain', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(54, 'quentin', 'flayac', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(55, 'rayane', 'slimani', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(56, 'richard', 'issa', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(57, 'samir', 'elasri', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(58, 'stephane1', 'ngo', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(59, 'theo', 'benmoussa', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(60, 'tio', 'gobin', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(61, 'vincent', 'duchesnay', 1, '2018-05-31 14:46:11', '2018-05-31 14:46:11', ''),
(63, 'amine', 'hadef', 1, '2018-06-04 09:05:11', '2018-06-04 09:05:11', ''),
(64, 'juliette', 'de-la-chatre', 1, '2018-06-04 09:06:15', '2018-06-04 09:06:15', ''),
(65, 'kevin', 'haboub', 1, '2018-06-04 09:06:15', '2018-06-04 09:06:15', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `admin`) VALUES
(1, 'Brian Boudrioux', 'brian1.boudrioux@epitech.eu', 'e0c9035898dd52fc65c41454cec9c4d2611bfb37', '51y0x933ntxeki81n646sq79xkr42c5gxuj6k8kx7j3429aj5f', NULL, NULL, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `days`
--
ALTER TABLE `days`
  ADD CONSTRAINT `days_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `edit_pangs`
--
ALTER TABLE `edit_pangs`
  ADD CONSTRAINT `edit_pangs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `log_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
