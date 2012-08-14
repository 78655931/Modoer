<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_travelers`;");
E_C("CREATE TABLE `modoer_travelers` (
  `tid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tusername` varchar(16) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `uid` (`uid`,`addtime`),
  KEY `tuid` (`tuid`,`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>