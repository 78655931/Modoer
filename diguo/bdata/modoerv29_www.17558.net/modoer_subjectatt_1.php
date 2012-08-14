<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectatt`;");
E_C("CREATE TABLE `modoer_subjectatt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `att_catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`,`attid`),
  KEY `attid` (`attid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>