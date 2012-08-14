<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_hook`;");
E_C("CREATE TABLE `modoer_hook` (
  `hookid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `hook_module` varchar(30) NOT NULL DEFAULT '',
  `hook_position` varchar(60) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `des` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`hookid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>