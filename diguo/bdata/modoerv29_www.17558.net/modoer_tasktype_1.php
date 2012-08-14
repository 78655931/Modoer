<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_tasktype`;");
E_C("CREATE TABLE `modoer_tasktype` (
  `ttid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`ttid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_tasktype` values('1','review:post','点评类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('2','review:flower','鲜花类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('3','member:avatar','头像类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('4','item:subject','主题类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('5','item:picture','图片类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('6','item:favorite','关注类任务','MouferStudio','1.0');");
E_D("replace into `modoer_tasktype` values('7','article:post','文章类任务','MouferStudio','1.0');");

require("../../inc/footer.php");
?>