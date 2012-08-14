<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_exchange_gifts`;");
E_C("CREATE TABLE `modoer_exchange_gifts` (
  `giftid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pattern` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reviewed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `randomcodelen` tinyint(1) NOT NULL DEFAULT '0',
  `randomcode` varchar(50) NOT NULL DEFAULT '',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `point3` int(10) unsigned NOT NULL DEFAULT '0',
  `point4` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype2` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype3` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype4` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `timenum` int(10) unsigned NOT NULL DEFAULT '0',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `salevolume` int(11) unsigned NOT NULL DEFAULT '0',
  `allowtime` varchar(255) NOT NULL DEFAULT '',
  `usergroup` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`giftid`),
  KEY `available` (`available`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>