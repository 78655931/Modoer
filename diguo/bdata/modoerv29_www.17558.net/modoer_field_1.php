<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_field`;");
E_C("CREATE TABLE `modoer_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` smallint(5) NOT NULL DEFAULT '0',
  `tablename` varchar(25) NOT NULL DEFAULT '',
  `fieldname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `note` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `allownull` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `isadminfield` varchar(1) NOT NULL DEFAULT '0',
  `show_list` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_detail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `regular` varchar(255) NOT NULL DEFAULT '',
  `errmsg` varchar(255) NOT NULL DEFAULT '',
  `datatype` varchar(60) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>