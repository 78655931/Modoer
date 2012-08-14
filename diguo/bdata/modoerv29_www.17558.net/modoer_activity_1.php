<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_activity`;");
E_C("CREATE TABLE `modoer_activity` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `reviews` smallint(5) unsigned NOT NULL DEFAULT '0',
  `subjects` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `reviews` (`reviews`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>