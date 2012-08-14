<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_pay`;");
E_C("CREATE TABLE `modoer_pay` (
  `payid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `order_flag` varchar(30) NOT NULL DEFAULT '',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order_name` varchar(255) NOT NULL DEFAULT '',
  `payment_orderid` varchar(60) NOT NULL DEFAULT '',
  `payment_name` varchar(60) NOT NULL DEFAULT '',
  `creation_time` int(10) NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `pay_status` tinyint(1) NOT NULL DEFAULT '0',
  `my_status` tinyint(1) NOT NULL DEFAULT '0',
  `notify_url` varchar(255) NOT NULL DEFAULT '',
  `callback_url` varchar(255) NOT NULL DEFAULT '',
  `royalty` tinytext NOT NULL,
  PRIMARY KEY (`payid`),
  KEY `order_flag` (`order_flag`,`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>