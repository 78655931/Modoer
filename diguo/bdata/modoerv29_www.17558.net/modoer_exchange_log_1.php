<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_exchange_log`;");
E_C("CREATE TABLE `modoer_exchange_log` (
  `exchangeid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL DEFAULT '',
  `giftid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `giftname` varchar(200) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` varchar(50) NOT NULL DEFAULT '',
  `number` int(10) unsigned NOT NULL DEFAULT '1',
  `pay_style` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `status_extra` varchar(255) NOT NULL DEFAULT '',
  `exchangetime` int(10) NOT NULL DEFAULT '0',
  `contact` mediumtext NOT NULL,
  `checker` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`exchangeid`),
  KEY `uid` (`uid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>