<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_spaces`;");
E_C("CREATE TABLE `modoer_spaces` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `space_styleid` smallint(3) NOT NULL DEFAULT '0',
  `spacename` varchar(30) NOT NULL DEFAULT '',
  `spacedescribe` varchar(50) NOT NULL DEFAULT '',
  `pageview` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `pageviews` (`pageview`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>