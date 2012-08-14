<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_admin`;");
E_C("CREATE TABLE `modoer_admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(24) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `admintype` tinyint(3) NOT NULL DEFAULT '0',
  `is_founder` char(1) NOT NULL DEFAULT 'N',
  `logintime` int(10) NOT NULL DEFAULT '0',
  `loginip` varchar(20) NOT NULL DEFAULT '',
  `logincount` int(10) unsigned NOT NULL DEFAULT '0',
  `mycitys` text NOT NULL,
  `mymodules` text NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `validtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `adminname` (`adminname`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_admin` values('1','admin','21232f297a57a5a743894a0e4a801fc3','admin@17558.net','1','Y','1344574561','127.0.0.1','3','','','0','0');");

require("../../inc/footer.php");
?>