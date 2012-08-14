<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_templates`;");
E_C("CREATE TABLE `modoer_templates` (
  `templateid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `copyright` varchar(45) NOT NULL DEFAULT '',
  `tpltype` varchar(15) NOT NULL DEFAULT '',
  `bind` tinyint(1) NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL DEFAULT '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL DEFAULT 'point1',
  PRIMARY KEY (`templateid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_templates` values('1','默认模板','default','Moufer Studio','main','1','0','point1');");
E_D("replace into `modoer_templates` values('2','商铺风格1','store_1','','item','0','10','point1');");

require("../../inc/footer.php");
?>