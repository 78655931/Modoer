<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_bcastr`;");
E_C("CREATE TABLE `modoer_bcastr` (
  `bcastr_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupname` varchar(15) NOT NULL DEFAULT 'index',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `itemtitle` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `item_url` varchar(255) NOT NULL DEFAULT '',
  `orders` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bcastr_id`),
  KEY `groupname` (`groupname`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_bcastr` values('1','0','index','1','ModoerµãÆÀÏµÍ³','uploads/bcastr/25_1275267815.jpg','http://www.17558.net','1');");

require("../../inc/footer.php");
?>