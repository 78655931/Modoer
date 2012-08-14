<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_coupons`;");
E_C("CREATE TABLE `modoer_coupons` (
  `couponid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `des` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `effect1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prints` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `grade` smallint(5) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pageview` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `closed_comment` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`couponid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>