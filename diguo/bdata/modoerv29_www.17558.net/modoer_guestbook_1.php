<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_guestbook`;");
E_C("CREATE TABLE `modoer_guestbook` (
  `guestbookid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `uid` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reply` text NOT NULL,
  `replytime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`guestbookid`),
  KEY `id` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>