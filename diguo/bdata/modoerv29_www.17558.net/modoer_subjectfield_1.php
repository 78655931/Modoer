<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectfield`;");
E_C("CREATE TABLE `modoer_subjectfield` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) NOT NULL DEFAULT '0',
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
  `show_side` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `regular` varchar(255) NOT NULL DEFAULT '',
  `errmsg` varchar(255) NOT NULL DEFAULT '',
  `datatype` varchar(60) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_subjectfield` values('1','1','subject','aid','����','','','','','area','6','0','1','2','0','0','1','0','/[0-9]+/','δѡ�����','varchar(6)','a:1:{s:7:\"default\";s:1:\"0\";}','0');");
E_D("replace into `modoer_subjectfield` values('2','1','subject','catid','����','','','','','category','1','0','1','2','0','0','1','0','/[0-9]+/','δѡ�����','smallint(5)','a:1:{s:7:\"default\";s:1:\"0\";}','0');");
E_D("replace into `modoer_subjectfield` values('3','1','subject','name','����','','','','','text','2','0','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');");
E_D("replace into `modoer_subjectfield` values('4','1','subject','subname','������','','','','','text','3','1','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');");
E_D("replace into `modoer_subjectfield` values('5','1','subject','mappoint','��ͼ����','','','','','mappoint','4','0','0','1','0','0','1','0','/[0-9a-z]+,[0-9a-z]+/i','��ͼ���겻��ȷ','varchar(60)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');");
E_D("replace into `modoer_subjectfield` values('6','1','subject','video','��Ƶ��ַ','','','','','video','5','1','0','1','0','1','1','0','','','varchar(255)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');");
E_D("replace into `modoer_subjectfield` values('8','1','subject','description','���','','','','','text','7','1','0','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:60;}','0');");
E_D("replace into `modoer_subjectfield` values('10','1','subject','level','�ȼ�','','','','','level','92','0','1','1','1','0','1','0','/[0-9]+/','δѡ���������ȼ�','tinyint(3)','a:1:{s:7:\"default\";i:0;}','0');");
E_D("replace into `modoer_subjectfield` values('11','1','subject','finer','�Ƽ���','','','','','numeric','91','1','0','1','1','0','0','0','','','SMALLINT(5)','a:4:{s:3:\"min\";i:0;s:3:\"max\";i:255;s:5:\"point\";s:1:\"0\";s:7:\"default\";s:1:\"0\";}','0');");
E_D("replace into `modoer_subjectfield` values('12','1','subject_shops','content','��ϸ����','','','','','textarea','90','0','0','1','0','0','1','0','','','MEDIUMTEXT','a:6:{s:5:\"width\";s:3:\"99%\";s:6:\"height\";s:5:\"200px\";s:4:\"html\";s:1:\"2\";s:7:\"default\";s:0:\"\";s:11:\"charnum_sup\";i:0;s:11:\"charnum_sub\";i:1000;}','0');");

require("../../inc/footer.php");
?>