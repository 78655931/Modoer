<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_exchange_lottery`;");
E_C("CREATE TABLE `modoer_exchange_lottery` (
  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `lotterycode` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>