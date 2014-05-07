-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 07, 2014 at 11:35 AM
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
  `name` varchar(64) NOT NULL,
  `code` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `game_party`
--

CREATE TABLE IF NOT EXISTS `game_party` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
`id` int(10) unsigned NOT NULL,
  `provider_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
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
  `name` varchar(128) NOT NULL,
  `country_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
`id` int(10) unsigned NOT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `payment_id` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_template`
--

CREATE TABLE IF NOT EXISTS `ticket_template` (
`id` int(10) unsigned NOT NULL,
  `game_party_id` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `price_date_start` datetime DEFAULT NULL,
  `price_date_end` datetime DEFAULT NULL,
  `is_cash` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(10) unsigned NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` char(60) NOT NULL,
  `nick` varchar(64) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `team_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_team_manager` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remember_token` char(60) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `nick`, `birth_date`, `team_id`, `is_team_manager`, `is_validated`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'robin@jesp.ru', '$2a$04$hYpb8G.ye4mzVWqiadQW.ubE5PL7pA4Jr2VgermN4pzBaVaJe1KUK', 'Robin', NULL, 0, 0, 0, 'ubktSGrBu4QK365JERGMPsOgxgqw4gIwqWYQitT3xoxNvL9ZBMIo0Y3B0D1E', '2014-05-06 00:00:00', '2014-05-06 13:56:45');

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
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `game_party`
--
ALTER TABLE `game_party`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ticket_template`
--
ALTER TABLE `ticket_template`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
