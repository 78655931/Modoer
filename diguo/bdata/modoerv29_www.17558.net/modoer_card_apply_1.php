<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_card_apply`;");
E_C("CREATE TABLE `modoer_card_apply` (
  `applyid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `linkman` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(10) NOT NULL DEFAULT '',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `coin` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `comment` text NOT NULL,
  `checker` varchar(30) NOT NULL DEFAULT '',
  `checktime` int(10) NOT NULL DEFAULT '0',
  `checkmsg` text NOT NULL,
  PRIMARY KEY (`applyid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>