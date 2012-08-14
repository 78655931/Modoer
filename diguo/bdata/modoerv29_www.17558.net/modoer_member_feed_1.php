<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_member_feed`;");
E_C("CREATE TABLE `modoer_member_feed` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL DEFAULT '',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL DEFAULT '',
  `module` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(30) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>