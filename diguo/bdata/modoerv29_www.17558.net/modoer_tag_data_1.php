<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_tag_data`;");
E_C("CREATE TABLE `modoer_tag_data` (
  `stid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `tgid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tagname` varchar(25) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`stid`),
  KEY `tagid` (`tagid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>