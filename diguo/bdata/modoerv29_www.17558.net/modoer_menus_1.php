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
E_D("replace into `modoer_menus` values('1','0','0','1','头部菜单','','','','','1');");
E_D("replace into `modoer_menus` values('49','1','0','0','首页','index','modoer/index','','_self','1');");
E_D("replace into `modoer_menus` values('3','0','0','1','后台快捷菜单','','','','','5');");
E_D("replace into `modoer_menus` values('53','3','0','0','调用管理','','?module=modoer&act=datacall&op=list','','main','3');");
E_D("replace into `modoer_menus` values('54','3','0','0','更新网站缓存','','?module=modoer&act=tools&op=cache','','main','4');");
E_D("replace into `modoer_menus` values('62','1','0','0','主题','item_category','item/category','','','4');");
E_D("replace into `modoer_menus` values('80','0','0','1','个人空间菜单','','','','','4');");
E_D("replace into `modoer_menus` values('75','3','0','0','菜单管理','','?module=modoer&act=menu','','','5');");
E_D("replace into `modoer_menus` values('66','0','0','1','底部菜单','','','','','2');");
E_D("replace into `modoer_menus` values('68','66','0','0','联系我们','','#','','','0');");
E_D("replace into `modoer_menus` values('69','66','0','0','广告服务','','#','','','0');");
E_D("replace into `modoer_menus` values('70','66','0','0','服务条款','','#','','','0');");
E_D("replace into `modoer_menus` values('71','66','0','0','网站地图','','#','','','0');");
E_D("replace into `modoer_menus` values('72','66','0','0','使用帮助','','#','','','0');");
E_D("replace into `modoer_menus` values('73','66','0','0','诚聘英才','','#','','','0');");
E_D("replace into `modoer_menus` values('76','3','0','0','主题审核','','?module=item&act=subject_check','','','1');");
E_D("replace into `modoer_menus` values('77','3','0','0','点评审核','','?module=review&act=review&op=checklist','','','2');");
E_D("replace into `modoer_menus` values('81','80','0','0','首页','space_index','space/index/uid/(uid)','','','1');");
E_D("replace into `modoer_menus` values('82','80','0','0','我发表的点评','space_reviews','space/reviews/uid/(uid)','','','2');");
E_D("replace into `modoer_menus` values('83','80','0','0','我添加的主题','space_subjects','space/subjects/uid/(uid)','','','3');");
E_D("replace into `modoer_menus` values('84','80','0','0','我的好友','space_friends','space/friends/uid/(uid)','','','4');");
E_D("replace into `modoer_menus` values('88','1','0','0','礼品','exchange','exchange/index','','','9');");
E_D("replace into `modoer_menus` values('90','1','0','0','资讯','article','article/index','','','3');");
E_D("replace into `modoer_menus` values('93','1','0','0','会员卡','card','card/index','','','11');");
E_D("replace into `modoer_menus` values('94','1','0','0','优惠券','coupon','coupon/index','','','10');");
E_D("replace into `modoer_menus` values('95','1','0','0','相册','item_album','item/album','','','12');");
E_D("replace into `modoer_menus` values('96','1','0','0','产品库','product','product/index','','','13');");
E_D("replace into `modoer_menus` values('97','1','0','0','点评','review','review/index','','','14');");
E_D("replace into `modoer_menus` values('98','1','0','0','排行榜','item_subject_tops','item/tops','','','15');");
E_D("replace into `modoer_menus` values('99','1','0','0','交流区','discussion','discussion/index','','','16');");

require("../../inc/footer.php");
?>