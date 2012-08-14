<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_area`;");
E_C("CREATE TABLE `modoer_area` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(20) NOT NULL DEFAULT '',
  `initial` char(1) NOT NULL DEFAULT '',
  `name` varchar(16) NOT NULL DEFAULT '',
  `mappoint` varchar(50) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `templateid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `config` text NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `pid` (`pid`),
  KEY `level` (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_area` values('1','0','4','ningbo','','������','121.565151,29.877309','1','0','0','1','a:4:{s:9:\"mapapikey\";s:0:\"\";s:8:\"sitename\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";s:13:\"meta_keywords\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";s:16:\"meta_description\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";}');");
E_D("replace into `modoer_area` values('2','1','5','','','������','','2','0','0','1','');");
E_D("replace into `modoer_area` values('5','2','8','','','���׹㳡','','3','0','0','1','');");
E_D("replace into `modoer_area` values('3','1','6','','','������','','2','0','0','1','');");
E_D("replace into `modoer_area` values('6','3','9','','','���ſ�','','3','0','0','1','');");
E_D("replace into `modoer_area` values('4','1','7','','','������','','2','0','0','1','');");
E_D("replace into `modoer_area` values('7','4','10','','','����̲','','3','0','0','1','');");
E_D("replace into `modoer_area` values('8','0','11','qingdao','q','�ൺ��','120.38922,36.06343','1','0','0','1','a:4:{s:9:\"mapapikey\";s:0:\"\";s:8:\"sitename\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";s:13:\"meta_keywords\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";s:16:\"meta_description\";s:42:\"���������ѧϰ���о�ʹ�ã�����������ҵ��;\";}');");

require("../../inc/footer.php");
?>