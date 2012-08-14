<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_articles`;");
E_C("CREATE TABLE `modoer_articles` (
  `articleid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `att` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `havepic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL DEFAULT '',
  `subject` varchar(60) NOT NULL DEFAULT '',
  `keywords` varchar(100) NOT NULL DEFAULT '',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `grade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `digg` mediumint(8) NOT NULL DEFAULT '0',
  `closed_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `copyfrom` varchar(200) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `checker` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`articleid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_articles` values('1','0','2','','1275267900','1','0','0','0','admin','Modoer2.9点评系统介绍','','4','0','0','0','0','','Modoer2.9点评系统介绍','','','1','');");
E_D("replace into `modoer_articles` values('2','0','2','','1340285700','1','0','0','0','admin','好东西分享www.17558.net 免费分享精品网站源码','','0','0','0','0','0','','好东西分享 - 网罗精品软件、精品源码、精品网站，网罗一切免费web资源分享给大家！','','','1','');");

require("../../inc/footer.php");
?>