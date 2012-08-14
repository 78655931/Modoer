<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_pay_log`;");
E_C("CREATE TABLE `modoer_pay_log` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `port_orderid` varchar(60) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `cztype` varchar(15) DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `exchangetime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `retcode` varchar(10) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>