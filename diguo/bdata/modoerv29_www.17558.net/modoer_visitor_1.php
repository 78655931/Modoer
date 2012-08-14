<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_visitor`;");
E_C("CREATE TABLE `modoer_visitor` (
  `vid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `total` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`),
  KEY `sid` (`sid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>