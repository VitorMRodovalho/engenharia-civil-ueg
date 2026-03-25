
DROP TABLE IF EXISTS `#__facileforms_config`;
CREATE TABLE `#__facileforms_config` (
  `id` varchar(30) NOT NULL default '',
  `value` text,
  PRIMARY KEY (`id`)
) TYPE = MYISAM;

DROP TABLE IF EXISTS `#__facileforms_packages`;
CREATE TABLE `#__facileforms_packages` (
  `id` varchar(30) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `version` varchar(30) NOT NULL default '',
  `created` varchar(20) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `author` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `copyright` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
INSERT INTO `#__facileforms_packages` VALUES (
  '',
  'mypck_001',
  '0.0.1',
  '2005-07-31 22:21:23',
  'My First Package',
  'My Name',
  'my.name@my.domain',
  'http://www.my.domain',
  'This is the first package that I created',
  'This FacileForms package is released under the GNU/GPL license'
);

DROP TABLE IF EXISTS `#__facileforms_compmenus`;
CREATE TABLE `#__facileforms_compmenus` (
  `id` int(11) NOT NULL auto_increment,
  `package` VARCHAR( 30 ) NOT NULL default '',
  `parent` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `img`  varchar(255) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `page` int(11) NOT NULL default '1',
  `frame` tinyint(1) NOT NULL default '0',
  `border` tinyint(1) NOT NULL default '0',
  `params`  text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_forms`;
CREATE TABLE `#__facileforms_forms` (
  `id` int(11) NOT NULL auto_increment,
  `package` VARCHAR( 30 ) NOT NULL default '',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `runmode` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `description` text,
  `class1` varchar(30),
  `class2` varchar(30),
  `width` int(11) NOT NULL default '0',
  `widthmode` tinyint(1) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL default '0',
  `heightmode` tinyint(1) NOT NULL DEFAULT '0',
  `pages` int(11) NOT NULL default '1',
  `emailntf` tinyint(1) NOT NULL default '1',
  `emaillog` tinyint(1) NOT NULL default '1',
  `emailxml` tinyint(1) NOT NULL default '0',
  `emailadr` varchar(50) default NULL,
  `dblog` tinyint(1) NOT NULL default '1',
  `script1cond` tinyint(1) NOT NULL default '0',
  `script1id` int(11) default NULL,
  `script1code` text,
  `script2cond` tinyint(1) NOT NULL default '0',
  `script2id` int(11) default NULL,
  `script2code` text,
  `piece1cond` tinyint(1) NOT NULL default '0',
  `piece1id` int(11) default NULL,
  `piece1code` text,
  `piece2cond` tinyint(1) NOT NULL default '0',
  `piece2id` int(11) default NULL,
  `piece2code` text,
  `piece3cond` tinyint(1) NOT NULL default '0',
  `piece3id` int(11) default NULL,
  `piece3code` text,
  `piece4cond` tinyint(1) NOT NULL default '0',
  `piece4id` int(11) default NULL,
  `piece4code` text,
  `prevmode` tinyint(1) NOT NULL default '2',
  `prevwidth` int(11),
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_elements`;
CREATE TABLE `#__facileforms_elements` (
  `id` int(11) NOT NULL auto_increment,
  `form` int(11) NOT NULL default '0',
  `page` int(11) NOT NULL default '1',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `type` varchar(50) NOT NULL default '',
  `class1` VARCHAR(30),
  `class2` VARCHAR(30),
  `logging` tinyint(1) NOT NULL default '1',
  `posx` int(11) default NULL,
  `posxmode` TINYINT(1) NOT NULL DEFAULT '0',
  `posy` int(11) default NULL,
  `posymode` TINYINT(1) NOT NULL DEFAULT '0',
  `width` int(11) default NULL,
  `widthmode` TINYINT(1) NOT NULL DEFAULT '0',
  `height` int(11) default NULL,
  `heightmode` TINYINT(1) NOT NULL DEFAULT '0',
  `flag1` tinyint(1) NOT NULL default '0',
  `flag2` tinyint(1) NOT NULL default '0',
  `data1` text,
  `data2` text,
  `data3` text,
  `script1cond` tinyint(1) NOT NULL default '0',
  `script1id` int(11) default NULL,
  `script1code` text,
  `script1flag1` tinyint(1) NOT NULL default '0',
  `script1flag2` tinyint(1) NOT NULL default '0',
  `script2cond` tinyint(1) NOT NULL default '0',
  `script2id` int(11) default NULL,
  `script2code` text,
  `script2flag1` tinyint(1) NOT NULL default '0',
  `script2flag2` tinyint(1) NOT NULL default '0',
  `script2flag3` tinyint(1) NOT NULL default '0',
  `script2flag4` tinyint(1) NOT NULL default '0',
  `script2flag5` tinyint(1) NOT NULL default '0',
  `script3cond` tinyint(1) NOT NULL default '0',
  `script3id` int(11) default NULL,
  `script3code` text,
  `script3msg` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_scripts`;
CREATE TABLE `#__facileforms_scripts` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL default '0',
  `package` VARCHAR( 30 ) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `description` text,
  `type` varchar(30) NOT NULL default '',
  `code` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_pieces`;
CREATE TABLE `#__facileforms_pieces` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL default '0',
  `package` VARCHAR( 30 ) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `description` text,
  `type` varchar(30) NOT NULL default '',
  `code` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_records`;
CREATE TABLE `#__facileforms_records` (
  `id` int(11) NOT NULL auto_increment,
  `submitted` datetime NOT NULL default '0000-00-00 00:00:00',
  `form` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `ip` varchar(30) NOT NULL default '',
  `browser` varchar(255) NOT NULL default '',
  `opsys` varchar(255) NOT NULL default '',
  `provider` varchar(255) NOT NULL default '',
  `viewed` tinyint(1) NOT NULL default '0',
  `exported` tinyint(1) NOT NULL default '0',
  `archived` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__facileforms_subrecords`;
CREATE TABLE `#__facileforms_subrecords` (
  `id` int(11) NOT NULL auto_increment,
  `record` int(11) NOT NULL default '0',
  `element` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `type` varchar(30) NOT NULL default '',
  `value` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;