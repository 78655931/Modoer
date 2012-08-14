<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_reports`;");
E_C("CREATE TABLE `modoer_reports` (
  `reportid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  `reportcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `disposaltime` int(10) NOT NULL DEFAULT '0',
  `reportremark` mediumtext NOT NULL,
  `update_point` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`),
  KEY `disposal` (`disposal`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>