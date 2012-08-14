<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_getpassword`;");
E_C("CREATE TABLE `modoer_getpassword` (
  `getpwid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `secode` varchar(8) NOT NULL DEFAULT '',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `sort` enum('get_password','email_verify') NOT NULL DEFAULT 'get_password',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`getpwid`),
  KEY `secode` (`secode`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>