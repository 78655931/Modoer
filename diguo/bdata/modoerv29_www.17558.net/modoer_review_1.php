<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_review`;");
E_C("CREATE TABLE `modoer_review` (
  `rid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pcatid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort1` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort2` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort3` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort4` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort5` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort6` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort7` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort8` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `digest` tinyint(1) NOT NULL DEFAULT '0',
  `havepic` tinyint(1) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `isupdate` tinyint(1) NOT NULL DEFAULT '0',
  `flowers` int(8) unsigned NOT NULL DEFAULT '0',
  `responds` int(8) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `taggroup` text NOT NULL,
  `pictures` text NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `sid` (`id`,`status`),
  KEY `uid` (`uid`,`status`),
  KEY `city_id` (`city_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>