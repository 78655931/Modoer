<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_taggroup`;");
E_C("CREATE TABLE `modoer_taggroup` (
  `tgid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `des` varchar(200) NOT NULL DEFAULT '',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  PRIMARY KEY (`tgid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_taggroup` values('1','点评标签','商铺标签说明','1','');");

require("../../inc/footer.php");
?>