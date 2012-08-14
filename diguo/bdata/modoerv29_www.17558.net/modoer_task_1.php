<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_task`;");
E_C("CREATE TABLE `modoer_task` (
  `taskid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `taskflag` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `des` text NOT NULL,
  `icon` varchar(30) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `period` smallint(5) unsigned NOT NULL DEFAULT '0',
  `period_unit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pointtype` varchar(30) NOT NULL DEFAULT '',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `access_groupids` varchar(255) NOT NULL DEFAULT '',
  `applys` int(10) unsigned NOT NULL DEFAULT '0',
  `completes` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `reg_automatic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  PRIMARY KEY (`taskid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_task` values('1','1','member:avatar','�ϴ�ͷ��','ע���û��ϴ�һ���Լ���ͷ�񣬼��ɻ�û��ֽ������Ͽ�������ɣ�','','1315075860','0','0','0','point1','10','0','','0','0','0','0','');");
E_D("replace into `modoer_task` values('2','1','item:favorite','��ע����','������⣬��ע�Լ�ϲ���͹�ע�����⣬�������������ۼƹ�ע 3 �����⣬���ɻ�û��ֽ�����','','1315075920','0','0','0','point1','6','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');");
E_D("replace into `modoer_task` values('3','1','review:flower','�����ʻ�','������Ϊ�ǳ����ĵ�����Ϣ���� 3 ���ʻ����Ϳ��Ի�û��ֽ�����������ÿ�ܶ������ظ�����һ�Ρ�','','1315075980','0','1','2','point1','5','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');");
E_D("replace into `modoer_task` values('4','1','review:post','����������','���뱾�����ѡ��һ�����⣬�����Լ�����Щ����ĵ�����Ϣ������ 1 ƪ�����ɻ�û��ֽ��������ظ����롣','','1315076040','0','1','1','point1','5','1','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"1\";s:10:\"time_limit\";s:0:\"\";}');");

require("../../inc/footer.php");
?>