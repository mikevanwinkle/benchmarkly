CREATE TABLE IF NOT EXISTS `%sbenchmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT 'NOT NULL',
  `name` varchar(10) DEFAULT 'NOT NULL',
  `source` varchar(10) DEFAULT 'NOT NULL',
  `datanum` DECIMAL(10,2) NULL,
  `datachar` varchar(10) DEFAULT NULL,
  `datalong` longtext,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;