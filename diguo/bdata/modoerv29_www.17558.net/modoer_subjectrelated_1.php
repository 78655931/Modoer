<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectrelated`;");
E_C("CREATE TABLE `modoer_subjectrelated` (
  `related_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fieldid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `r_sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`related_id`),
  KEY `fieldid` (`fieldid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>