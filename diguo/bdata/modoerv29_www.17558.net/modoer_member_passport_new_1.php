<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_member_passport_new`;");
E_C("CREATE TABLE `modoer_member_passport_new` (
  `uid` mediumint(8) NOT NULL COMMENT '???uid',
  `psname` enum('weibo','qq','taobao','google','qzone') NOT NULL COMMENT '????????',
  `psuid` varchar(60) NOT NULL COMMENT '?????????id',
  `access_token` varchar(60) NOT NULL DEFAULT '' COMMENT '???????',
  `expired` int(10) unsigned NOT NULL COMMENT 'access_token???????',
  `isbind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '??????????',
  PRIMARY KEY (`uid`,`psname`),
  KEY `psname` (`psname`,`psuid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>