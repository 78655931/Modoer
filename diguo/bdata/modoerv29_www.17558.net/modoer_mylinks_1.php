<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_mylinks`;");
E_C("CREATE TABLE `modoer_mylinks` (
  `linkid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL DEFAULT '',
  `link` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(8) NOT NULL DEFAULT '0',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`linkid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_mylinks` values('3','�ö�������','http://www.17558.net','','','0','1');");
E_D("replace into `modoer_mylinks` values('4','���µ�Ӱ����','http://kan.17558.net','','','0','1');");
E_D("replace into `modoer_mylinks` values('5','�ⱸ������','http://host.17558.net','','','0','1');");

require("../../inc/footer.php");
?>