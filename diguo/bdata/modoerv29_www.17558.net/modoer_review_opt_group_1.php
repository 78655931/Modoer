<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_review_opt_group`;");
E_C("CREATE TABLE `modoer_review_opt_group` (
  `gid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_review_opt_group` values('1','默认点评项组','系统默认安装');");

require("../../inc/footer.php");
?>