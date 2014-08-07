CREATE TABLE IF NOT EXISTS `%benchmarkly_actions` ( 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NULL,
  `name` varchar(20) NULL,
  `description` varchar(255) NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;