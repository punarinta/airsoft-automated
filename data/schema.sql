--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `full_name` varchar(64) NOT NULL,
  `union_id` mediumint(8) unsigned NOT NULL,
  `staff_count` smallint(5) unsigned NOT NULL,
  `recruit_count` smallint(5) unsigned NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weapon_ids` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL COMMENT 'json?',
  `leader_nick` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `emblem` varchar(255) NOT NULL,
  `region_id` mediumint(8) unsigned NOT NULL,
  `camo_ids` varchar(255) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `recruiting` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
