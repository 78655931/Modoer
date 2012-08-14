<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_search_cache`;");
E_C("CREATE TABLE `modoer_search_cache` (
  `ssid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(60) NOT NULL DEFAULT '0',
  `count` mediumint(8) NOT NULL DEFAULT '0',
  `total` mediumint(8) NOT NULL DEFAULT '0',
  `catid` smallint(5) NOT NULL DEFAULT '0',
  `city_id` varchar(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ssid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>