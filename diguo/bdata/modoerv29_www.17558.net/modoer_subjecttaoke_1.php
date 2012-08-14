<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjecttaoke`;");
E_C("CREATE TABLE `modoer_subjecttaoke` (
  `user_id` int(10) unsigned NOT NULL,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `nick` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>