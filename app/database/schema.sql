-- phpMyAdmin SQL Dump
-- version 4.2.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2014 at 11:16 AM
-- Server version: 5.5.20
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(10) unsigned NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nick` varchar(64) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `team_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_team_manager` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

