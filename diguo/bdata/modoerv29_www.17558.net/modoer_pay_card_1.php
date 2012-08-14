<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_pay_card`;");
E_C("CREATE TABLE `modoer_pay_card` (
  `cardid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `cztype` varchar(15) DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `usetime` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`cardid`),
  UNIQUE KEY `number` (`number`),
  KEY `status` (`status`,`endtime`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>