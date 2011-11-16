DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_actions` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`plugin_fusinvdeploy_orders_id` INT( 11 ) NOT NULL DEFAULT  '0',
`itemtype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '0',
`items_id` INT( 11 ) NOT NULL DEFAULT  '0',
`ranking` int(11) DEFAULT NULL,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commands`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commands` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`exec` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
PRIMARY KEY (  `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_deletes`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_deletes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_mkdirs`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_mkdirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_moves`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_moves` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_copies`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_copies` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commandenvvariables`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commandenvvariables` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
`value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
`plugin_fusinvdeploy_commands_id` INT( 11 ) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_commandstatus`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_actions_commandstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'RETURNCODE_OK, RETURNCODE_KO, REGEX_OK, REGEX_KO',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plugin_fusinvdeploy_commands_id` INT( 11 ) NOT NULL DEFAULT  '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_checks`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_checks` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '' COMMENT  'winkeyExists, winkeyEquals, winkeyMissing, fileExists, fileMissing, fileSize, fileSHA512, freespaceGreater',
`path` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`value` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`ranking` int(11) DEFAULT NULL,
`plugin_fusinvdeploy_orders_id` INT( 11 ) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_fileparts`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_fileparts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sha512` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortsha512` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plugin_fusinvdeploy_orders_id` int(11) NOT NULL DEFAULT '0',
  `plugin_fusinvdeploy_files_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_files`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_p2p` tinyint(1) NOT NULL DEFAULT '0',
  `mimetype` char(255) NOT NULL DEFAULT 'na',
  `create_date` datetime NOT NULL,
  `p2p_retention_days` int(11) NOT NULL DEFAULT '0',
  `uncompress` tinyint(1) NOT NULL DEFAULT '0',
  `sha512` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `shortsha512` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `filesize` BIGINT NOT NULL DEFAULT  '0',
  `plugin_fusinvdeploy_orders_id` INT(11) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_orders`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'INSTALL, UNINSTALL, OTHER',
  `create_date` datetime NOT NULL,
  `plugin_fusinvdeploy_packages_id` INT( 11 ) NOT NULL DEFAULT  '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_packages`;
CREATE TABLE IF NOT EXISTS `glpi_plugin_fusinvdeploy_packages` (
  `id` int(11) DEFAULT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
PRIMARY KEY (  `id` ),
KEY `date_mod` (`date_mod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_files_mirrors`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_files_mirrors` (
`id` INT NOT NULL AUTO_INCREMENT ,
`entities_id` INT( 11 ) NOT NULL DEFAULT  '0',
`is_recursive` TINYINT( 1 ) NOT NULL DEFAULT  '0',
`plugin_fusinvdeploy_files_id` INT( 11 ) NOT NULL DEFAULT  '0',
`plugin_fusinvdeploy_mirrors_id` INT( 11 ) NOT NULL DEFAULT  '0',
`ranking` INT( 11 ) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `id` )
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_mirrors`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_mirrors` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`entities_id` INT( 11 ) NOT NULL DEFAULT  '0',
`is_recursive` TINYINT( 1 ) NOT NULL DEFAULT  '0',
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`comment` text COLLATE utf8_unicode_ci NOT NULL,
`date_mod` datetime DEFAULT NULL,
PRIMARY KEY (  `id` ),
KEY `date_mod` (`date_mod`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_actions_messages`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_actions_messages` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`message` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '' COMMENT  'info, postpone',
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_tasks`;
CREATE VIEW glpi_plugin_fusinvdeploy_tasks AS SELECT * FROM glpi_plugin_fusioninventory_tasks;

DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_taskjobs`;
CREATE VIEW glpi_plugin_fusinvdeploy_taskjobs
AS SELECT `id`,
`plugin_fusioninventory_tasks_id` AS `plugin_fusinvdeploy_tasks_id`,
`entities_id`, `name`, `date_creation`, `retry_nb`,
`retry_time`, `plugins_id`, `method`, `definition`,
`action`, `comment`, `users_id`, `status`,
`rescheduled_taskjob_id`, `statuscomments`,
`periodicity_count`, `periodicity_type`, `execution_id`
FROM glpi_plugin_fusioninventory_taskjobs;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_groups` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`entities_id` INT( 11 ) NOT NULL DEFAULT  '0',
`is_recursive` TINYINT( 1 ) NOT NULL DEFAULT  '0',
`name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`comment` text COLLATE utf8_unicode_ci NOT NULL,
`type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'STATIC, DYNAMIC',
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups_staticdatas`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_groups_staticdatas` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`groups_id` int(11) NOT NULL DEFAULT '0',
`itemtype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  '',
`items_id` INT( 11 ) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_fusinvdeploy_groups_dynamicdatas`;
CREATE TABLE  `glpi_plugin_fusinvdeploy_groups_dynamicdatas` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`groups_id` int(11) NOT NULL DEFAULT '0',
`fields_array` TEXT NOT NULL,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
