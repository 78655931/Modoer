<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_members`;");
E_C("CREATE TABLE `modoer_members` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(16) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `rmb` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `point` int(10) NOT NULL DEFAULT '0',
  `point1` int(10) NOT NULL DEFAULT '0',
  `point2` int(10) NOT NULL DEFAULT '0',
  `point3` int(10) NOT NULL DEFAULT '0',
  `point4` int(10) NOT NULL DEFAULT '0',
  `point5` int(10) NOT NULL DEFAULT '0',
  `point6` int(10) NOT NULL DEFAULT '0',
  `newmsg` smallint(5) unsigned NOT NULL DEFAULT '0',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(16) NOT NULL DEFAULT '',
  `logincount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `groupid` smallint(2) NOT NULL DEFAULT '1',
  `nextgroupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `subjects` int(10) unsigned NOT NULL DEFAULT '0',
  `reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `responds` int(10) unsigned NOT NULL DEFAULT '0',
  `flowers` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` int(10) unsigned NOT NULL DEFAULT '0',
  `follow` int(10) unsigned NOT NULL DEFAULT '0',
  `fans` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`),
  KEY `point` (`point`),
  KEY `point1` (`point1`),
  KEY `regip` (`regip`,`regdate`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>