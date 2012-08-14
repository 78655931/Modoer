<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_pmsgs`;");
E_C("CREATE TABLE `modoer_pmsgs` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `senduid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `recvuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `subject` varchar(60) NOT NULL DEFAULT '',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `new` tinyint(1) NOT NULL DEFAULT '1',
  `delflag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `senduid` (`senduid`,`posttime`),
  KEY `recvuid` (`recvuid`,`posttime`),
  KEY `new` (`new`,`recvuid`,`posttime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>