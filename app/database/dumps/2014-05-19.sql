-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2014 at 02:54 PM
-- Server version: 5.5.20
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(127) NOT NULL,
  `code` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Sweden', 'SWE', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `is_visible` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`, `owner_id`, `region_id`, `starts_at`, `ends_at`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'Test game 1', 1, 1, '2014-06-21 00:00:00', '2014-06-22 00:00:00', 1, NULL, NULL),
(2, 'Test game 2', 1, 4, '2014-06-28 00:00:00', '2014-06-29 00:00:00', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `game_party`
--

CREATE TABLE IF NOT EXISTS `game_party` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(127) NOT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `players_limit` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `game_party`
--

INSERT INTO `game_party` (`id`, `name`, `game_id`, `players_limit`, `created_at`, `updated_at`) VALUES
(1, 'Party 1', 1, 100, NULL, NULL),
(2, 'Party 2', 1, 200, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reminder`
--

CREATE TABLE IF NOT EXISTS `password_reminder` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
`id` int(10) unsigned NOT NULL,
  `provider_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `transaction_id` varchar(64) DEFAULT NULL,
  `amount` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(127) NOT NULL,
  `country_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`id`, `name`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'Stockholm', 1, NULL, NULL),
(2, 'Västerbotten', 1, NULL, NULL),
(3, 'Norrbotten', 1, NULL, NULL),
(4, 'Uppsala', 1, NULL, NULL),
(5, 'Östergötland', 1, NULL, NULL),
(6, 'Östergötland', 1, NULL, NULL),
(7, 'Jönköping', 1, NULL, NULL),
(8, 'Kronoberg', 1, NULL, NULL),
(9, 'Kalmar', 1, NULL, NULL),
(10, 'Gotland', 1, NULL, NULL),
(11, 'Blekinge', 1, NULL, NULL),
(12, 'Skåne', 1, NULL, NULL),
(13, 'Halland', 1, NULL, NULL),
(14, 'Västra Götaland', 1, NULL, NULL),
(15, 'Värmland', 1, NULL, NULL),
(16, 'Örebro', 1, NULL, NULL),
(17, 'Västmanland', 1, NULL, NULL),
(18, 'Dalarna', 1, NULL, NULL),
(19, 'Gävleborg', 1, NULL, NULL),
(20, 'Västernorrland', 1, NULL, NULL),
(21, 'Jämtland', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `region_id`, `owner_id`, `created_at`, `updated_at`) VALUES
(1, 'Some team', 1, 2, NULL, NULL),
(2, 'Org''s team', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
`id` int(10) unsigned NOT NULL,
  `game_party_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ticket_template_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `payment_id` int(10) unsigned DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `game_party_id`, `ticket_template_id`, `user_id`, `payment_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2, 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_template`
--

CREATE TABLE IF NOT EXISTS `ticket_template` (
`id` int(10) unsigned NOT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `game_party_id` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(10) unsigned NOT NULL,
  `price_date_start` datetime DEFAULT NULL,
  `price_date_end` datetime DEFAULT NULL,
  `is_cash` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ticket_template`
--

INSERT INTO `ticket_template` (`id`, `game_id`, `game_party_id`, `price`, `price_date_start`, `price_date_end`, `is_cash`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 15000, '2014-05-01 00:00:00', '2014-06-08 00:00:00', 0, NULL, NULL, NULL),
(2, 1, 1, 10000, '2014-05-01 00:00:00', '2014-05-31 00:00:00', 0, NULL, NULL, NULL),
(3, 1, 2, 11000, '2014-05-01 00:00:00', '2014-06-08 00:00:00', 1, NULL, NULL, NULL),
(4, 1, 1, 12300, '2014-06-01 00:00:00', '2014-06-08 00:00:00', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `nick` varchar(63) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `team_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_team_manager` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remember_token` char(60) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `nick`, `birth_date`, `team_id`, `is_team_manager`, `is_validated`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'org@jesp.ru', '$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW', 'Organizer', '2014-05-01', 2, 0, 1, 'hSnr5umMTQzCxlvOHPxgAoJB9hTVObiDHB1jh3LePFSYByDFqcmCXvpHgGGJ', '2014-05-06 00:00:00', '2014-05-13 07:57:41'),
(2, 'player-1@jesp.ru', '$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW', 'Player 1', NULL, 1, 1, 0, '5fY7bA7RWoiUC4snYRONq9Bjjes5An4lIzrOC1gqlrdRKNuutWuLx2tq7rgr', '2014-05-06 00:00:00', '2014-05-19 13:06:57'),
(3, 'player-2@jesp.ru', '$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW', 'Player 2', NULL, 1, 0, 0, NULL, '2014-05-06 00:00:00', '2014-05-10 15:35:39'),
(4, 'player-3@jesp.ru', '$2y$10$IiMiFJ5iuwGHXraFLPeBR..3QeUUVtPz6dP.Pndo5tLXZQpAZCnXW', 'Player 3', NULL, 1, 0, 0, NULL, '2014-05-06 00:00:00', '2014-05-10 15:35:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country`
--
ALTER TABLE `country`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_party`
--
ALTER TABLE `game_party`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reminder`
--
ALTER TABLE `password_reminder`
 ADD KEY `password_reminder_email_index` (`email`), ADD KEY `password_reminder_token_index` (`token`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_template`
--
ALTER TABLE `ticket_template`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `game_party`
--
ALTER TABLE `game_party`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ticket_template`
--
ALTER TABLE `ticket_template`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
