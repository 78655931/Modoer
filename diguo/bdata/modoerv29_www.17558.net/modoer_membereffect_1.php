<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_membereffect`;");
E_C("CREATE TABLE `modoer_membereffect` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `effect1` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `effect2` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `effect3` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>