<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_album`;");
E_C("CREATE TABLE `modoer_album` (
  `albumid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `des` text NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `num` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`albumid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>