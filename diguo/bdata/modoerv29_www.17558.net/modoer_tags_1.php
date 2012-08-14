<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_tags`;");
E_C("CREATE TABLE `modoer_tags` (
  `tagid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tagname` varchar(20) NOT NULL DEFAULT '',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`),
  KEY `total` (`total`),
  KEY `closed` (`closed`),
  KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>