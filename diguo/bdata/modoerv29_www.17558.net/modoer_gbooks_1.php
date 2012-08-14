<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_gbooks`;");
E_C("CREATE TABLE `modoer_gbooks` (
  `gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gbuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gbusername` varchar(16) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `uid` (`uid`,`posttime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>