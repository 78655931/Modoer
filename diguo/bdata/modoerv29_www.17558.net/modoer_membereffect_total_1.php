<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_membereffect_total`;");
E_C("CREATE TABLE `modoer_membereffect_total` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `effect1` int(10) unsigned NOT NULL DEFAULT '0',
  `effect2` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>