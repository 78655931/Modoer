<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_att_list`;");
E_C("CREATE TABLE `modoer_att_list` (
  `attid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT '',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`attid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_att_list` values('1','category','1','��ʳ','0','');");
E_D("replace into `modoer_att_list` values('2','category','2','������','0','');");
E_D("replace into `modoer_att_list` values('3','category','3','����','0','');");
E_D("replace into `modoer_att_list` values('4','area','1','������','0','');");
E_D("replace into `modoer_att_list` values('5','area','2','������','0','');");
E_D("replace into `modoer_att_list` values('6','area','3','������','0','');");
E_D("replace into `modoer_att_list` values('7','area','4','������','0','');");
E_D("replace into `modoer_att_list` values('8','area','5','���׹㳡','0','');");
E_D("replace into `modoer_att_list` values('9','area','6','���ſ�','0','');");
E_D("replace into `modoer_att_list` values('10','area','7','����̲','0','');");
E_D("replace into `modoer_att_list` values('11','area','8','�ൺ��','0','');");

require("../../inc/footer.php");
?>