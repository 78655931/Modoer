<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_digest_pay`;");
E_C("CREATE TABLE `modoer_digest_pay` (
  `payid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6') NOT NULL DEFAULT 'point1',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `gain_uid` mediumint(8) NOT NULL DEFAULT '0',
  `gain_price` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`payid`),
  KEY `id` (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>