<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_comment`;");
E_C("CREATE TABLE `modoer_comment` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `grade` tinyint(2) NOT NULL DEFAULT '0',
  `effect1` int(10) unsigned NOT NULL DEFAULT '0',
  `effect2` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  KEY `idtype` (`idtype`,`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>