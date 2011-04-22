CREATE TABLE IF NOT EXISTS `#__contactmap_marqueurs` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `url` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
