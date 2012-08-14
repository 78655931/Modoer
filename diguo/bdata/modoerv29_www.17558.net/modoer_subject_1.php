<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subject`;");
E_C("CREATE TABLE `modoer_subject` (
  `sid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domain` char(50) NOT NULL DEFAULT '',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `aid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sub_catids` varchar(255) NOT NULL DEFAULT '',
  `minor_catids` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `subname` varchar(60) NOT NULL DEFAULT '',
  `avgsort` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort1` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort2` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort3` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort4` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort5` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort6` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort7` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `sort8` decimal(4,2) unsigned NOT NULL DEFAULT '0.00',
  `avgprice` int(10) unsigned NOT NULL DEFAULT '0',
  `best` int(10) unsigned NOT NULL DEFAULT '0',
  `reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `guestbooks` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` int(10) unsigned NOT NULL DEFAULT '0',
  `pageviews` int(10) unsigned NOT NULL DEFAULT '1',
  `products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `coupons` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `finer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `owner` varchar(20) NOT NULL DEFAULT '',
  `cuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `creator` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `video` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `map_lng` decimal(8,5) NOT NULL DEFAULT '0.00000',
  `map_lat` decimal(8,5) NOT NULL DEFAULT '0.00000',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`),
  KEY `name` (`name`),
  KEY `doamin` (`domain`),
  KEY `catid` (`catid`,`city_id`,`addtime`),
  KEY `catid_2` (`catid`),
  KEY `aid` (`pid`,`aid`),
  KEY `pid` (`pid`,`city_id`,`addtime`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>