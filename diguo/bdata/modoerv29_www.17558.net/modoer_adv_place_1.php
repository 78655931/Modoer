<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_adv_place`;");
E_C("CREATE TABLE `modoer_adv_place` (
  `apid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `templateid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `enabled` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`apid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_adv_place` values('1','0','��ҳ_�в����','��ҳ�Ƽ������·����λ','<div class=\"ix_foo\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('2','0','������ҳ_���','����ģ�����ҳ���糤��ͼƬ���','<div class=\"art_ix\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('3','0','��������ҳ_��������','����������ҳ���µ����б�ڶ��м���Ĺ��','<div style=\"padding-bottom:10px;border-bottom:1px dashed #ddd;margin-bottom:10px;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">\$ad[code]</div>\r\n{getempty(ad)}\r\n<center>���λ����</center>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('4','0','�����б�ҳ_�б����','������ģ����б�ҳ�棬�б�ڶ������һ�����','<div style=\"padding-bottom:5px;border-bottom:1px dashed #ddd;margin:5px 0;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">\$ad[code]</div>\r\n{getempty(ad)}\r\n<center>���λ����</center>\r\n{/get}\r\n</div>','Y');");

require("../../inc/footer.php");
?>