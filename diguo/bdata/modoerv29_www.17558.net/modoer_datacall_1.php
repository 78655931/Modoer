<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_datacall`;");
E_C("CREATE TABLE `modoer_datacall` (
  `callid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(60) NOT NULL DEFAULT '',
  `calltype` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `fun` varchar(60) NOT NULL DEFAULT '',
  `var` varchar(60) NOT NULL DEFAULT '',
  `cachetime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `expression` text NOT NULL,
  `tplname` varchar(200) NOT NULL DEFAULT '',
  `empty_tplname` varchar(200) NOT NULL DEFAULT '',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`callid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_datacall` values('5','item','sql','主题_会员参与','sql','mydata','1000','a:6:{s:4:\"from\";s:72:\"{dbpre}membereffect_total mt LEFT JOIN {dbpre}subject s ON (mt.id=s.sid)\";s:6:\"select\";s:52:\"mt.{effect} as effect,s.sid,s.catid,s.name,s.subname\";s:5:\"where\";s:67:\"mt.idtype=''{idtype}'' AND mt.{effect}>0 AND s.city_id IN ({city_id})\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:16:\"mt.{effect} DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_effect_li','empty_li','0','');");
E_D("replace into `modoer_datacall` values('8','item','sql','主题_相关主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:68:\"city_id IN ({city_id}) and name=''{name}'' and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');");
E_D("replace into `modoer_datacall` values('6','item','sql','主题_同类主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:68:\"city_id IN ({city_id}) and catid={catid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');");
E_D("replace into `modoer_datacall` values('7','item','sql','主题_附近主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:37:\"aid={aid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');");
E_D("replace into `modoer_datacall` values('11','item','sql','首页_推荐主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:46:\"sid,aid,name,subname,avgsort,thumb,description\";s:5:\"where\";s:61:\"city_id IN ({city_id}) AND pid={pid} AND status=1 AND finer>0\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:10:\"finer DESC\";s:5:\"limit\";s:3:\"0,8\";}','index_subject_finer','empty_div','0','');");
E_D("replace into `modoer_datacall` values('16','product','sql','产品_主题产品','sql','mydata','1000','a:7:{s:4:\"from\";s:14:\"{dbpre}product\";s:6:\"select\";s:59:\"pid,catid,subject,grade,description,thumb,comments,pageview\";s:5:\"where\";s:22:\"sid={sid} AND status=1\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:24:\"grade DESC,comments DESC\";s:5:\"limit\";s:4:\"0,10\";s:9:\"cachetime\";s:4:\"1000\";}','product_pic_li','empty_li','0','');");

require("../../inc/footer.php");
?>