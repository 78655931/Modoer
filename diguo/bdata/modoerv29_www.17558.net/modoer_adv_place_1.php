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
E_D("replace into `modoer_adv_place` values('1','0','首页_中部广告','首页推荐主题下方广告位','<div class=\"ix_foo\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('2','0','新闻首页_广告','新闻模块的首页中午长条图片广告','<div class=\"art_ix\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('3','0','主题内容页_点评间广告','在主题内容页坐下点评列表第二行加入的广告','<div style=\"padding-bottom:10px;border-bottom:1px dashed #ddd;margin-bottom:10px;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">\$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');");
E_D("replace into `modoer_adv_place` values('4','0','主题列表页_列表间广告','在主题模块的列表页面，列表第二层加入一个广告','<div style=\"padding-bottom:5px;border-bottom:1px dashed #ddd;margin:5px 0;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">\$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');");

require("../../inc/footer.php");
?>