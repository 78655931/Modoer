<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectlink`;");
E_C("CREATE TABLE `modoer_subjectlink` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `flagid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `flag` varchar(30) NOT NULL DEFAULT '',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `flagid` (`flagid`,`flag`),
  KEY `sid` (`sid`,`flag`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>