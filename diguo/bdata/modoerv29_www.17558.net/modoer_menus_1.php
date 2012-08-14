<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_menus`;");
E_C("CREATE TABLE `modoer_menus` (
  `menuid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `isclosed` tinyint(1) NOT NULL DEFAULT '0',
  `isfolder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `scriptnav` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(60) NOT NULL DEFAULT '',
  `target` varchar(15) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`menuid`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_menus` values('1','0','0','1','ͷ���˵�','','','','','1');");
E_D("replace into `modoer_menus` values('49','1','0','0','��ҳ','index','modoer/index','','_self','1');");
E_D("replace into `modoer_menus` values('3','0','0','1','��̨��ݲ˵�','','','','','5');");
E_D("replace into `modoer_menus` values('53','3','0','0','���ù���','','?module=modoer&act=datacall&op=list','','main','3');");
E_D("replace into `modoer_menus` values('54','3','0','0','������վ����','','?module=modoer&act=tools&op=cache','','main','4');");
E_D("replace into `modoer_menus` values('62','1','0','0','����','item_category','item/category','','','4');");
E_D("replace into `modoer_menus` values('80','0','0','1','���˿ռ�˵�','','','','','4');");
E_D("replace into `modoer_menus` values('75','3','0','0','�˵�����','','?module=modoer&act=menu','','','5');");
E_D("replace into `modoer_menus` values('66','0','0','1','�ײ��˵�','','','','','2');");
E_D("replace into `modoer_menus` values('68','66','0','0','��ϵ����','','#','','','0');");
E_D("replace into `modoer_menus` values('69','66','0','0','������','','#','','','0');");
E_D("replace into `modoer_menus` values('70','66','0','0','��������','','#','','','0');");
E_D("replace into `modoer_menus` values('71','66','0','0','��վ��ͼ','','#','','','0');");
E_D("replace into `modoer_menus` values('72','66','0','0','ʹ�ð���','','#','','','0');");
E_D("replace into `modoer_menus` values('73','66','0','0','��ƸӢ��','','#','','','0');");
E_D("replace into `modoer_menus` values('76','3','0','0','�������','','?module=item&act=subject_check','','','1');");
E_D("replace into `modoer_menus` values('77','3','0','0','�������','','?module=review&act=review&op=checklist','','','2');");
E_D("replace into `modoer_menus` values('81','80','0','0','��ҳ','space_index','space/index/uid/(uid)','','','1');");
E_D("replace into `modoer_menus` values('82','80','0','0','�ҷ���ĵ���','space_reviews','space/reviews/uid/(uid)','','','2');");
E_D("replace into `modoer_menus` values('83','80','0','0','����ӵ�����','space_subjects','space/subjects/uid/(uid)','','','3');");
E_D("replace into `modoer_menus` values('84','80','0','0','�ҵĺ���','space_friends','space/friends/uid/(uid)','','','4');");
E_D("replace into `modoer_menus` values('88','1','0','0','��Ʒ','exchange','exchange/index','','','9');");
E_D("replace into `modoer_menus` values('90','1','0','0','��Ѷ','article','article/index','','','3');");
E_D("replace into `modoer_menus` values('93','1','0','0','��Ա��','card','card/index','','','11');");
E_D("replace into `modoer_menus` values('94','1','0','0','�Ż�ȯ','coupon','coupon/index','','','10');");
E_D("replace into `modoer_menus` values('95','1','0','0','���','item_album','item/album','','','12');");
E_D("replace into `modoer_menus` values('96','1','0','0','��Ʒ��','product','product/index','','','13');");
E_D("replace into `modoer_menus` values('97','1','0','0','����','review','review/index','','','14');");
E_D("replace into `modoer_menus` values('98','1','0','0','���а�','item_subject_tops','item/tops','','','15');");
E_D("replace into `modoer_menus` values('99','1','0','0','������','discussion','discussion/index','','','16');");

require("../../inc/footer.php");
?>