<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_pictures`;");
E_C("CREATE TABLE `modoer_pictures` (
  `picid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  `browse` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`picid`),
  KEY `uid` (`uid`,`sid`),
  KEY `sid` (`sid`,`status`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>