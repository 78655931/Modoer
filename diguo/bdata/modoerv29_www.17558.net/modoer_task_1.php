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
E_D("replace into `modoer_task` values('1','1','member:avatar','上传头像','注册用户上传一个自己的头像，即可获得积分奖励，赶快来参与吧！','','1315075860','0','0','0','point1','10','0','','0','0','0','0','');");
E_D("replace into `modoer_task` values('2','1','item:favorite','关注主题','浏览主题，关注自己喜欢和关注的主题，从申请任务起，累计关注 3 个主题，即可获得积分奖励。','','1315075920','0','0','0','point1','6','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');");
E_D("replace into `modoer_task` values('3','1','review:flower','赠送鲜花','给你认为非常棒的点评信息赠送 3 朵鲜花，就可以获得积分奖励，本任务每周都可以重复申请一次。','','1315075980','0','1','2','point1','5','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');");
E_D("replace into `modoer_task` values('4','1','review:post','添加主题点评','申请本任务后，选择一个主题，发表自己对这些主题的点评信息，发表 1 篇，即可获得积分奖励，可重复申请。','','1315076040','0','1','1','point1','5','1','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"1\";s:10:\"time_limit\";s:0:\"\";}');");

require("../../inc/footer.php");
?>