<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_member_point_log`;");
E_C("CREATE TABLE `modoer_member_point_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `out_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `out_username` varchar(25) NOT NULL DEFAULT '',
  `out_point` varchar(20) NOT NULL DEFAULT '',
  `out_value` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `in_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `in_username` varchar(25) NOT NULL DEFAULT '',
  `in_point` varchar(20) NOT NULL DEFAULT '',
  `in_value` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `des` text NOT NULL,
  `extra` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `out_uid` (`out_uid`,`in_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>