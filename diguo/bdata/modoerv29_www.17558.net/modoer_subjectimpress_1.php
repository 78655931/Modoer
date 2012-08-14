<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectimpress`;");
E_C("CREATE TABLE `modoer_subjectimpress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `total` int(8) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`,`total`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>