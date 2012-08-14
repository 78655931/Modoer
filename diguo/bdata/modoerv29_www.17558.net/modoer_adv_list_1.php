<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_adv_list`;");
E_C("CREATE TABLE `modoer_adv_list` (
  `adid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `apid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adname` varchar(60) NOT NULL DEFAULT '',
  `sort` enum('word','flash','code','img') NOT NULL,
  `begintime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `code` text NOT NULL,
  `attr` char(10) NOT NULL DEFAULT '',
  `ader` varchar(255) NOT NULL DEFAULT '',
  `adtel` varchar(255) NOT NULL DEFAULT '',
  `ademail` varchar(255) NOT NULL DEFAULT '',
  `enabled` char(1) NOT NULL DEFAULT 'Y',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_adv_list` values('1','1','0','Modoer2.0发布','img','1289232000','0','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:36:\"/uploads/adv/MONTH/15_1340285502.gif\";s:9:\"img_width\";s:3:\"708\";s:10:\"img_height\";s:2:\"75\";s:8:\"img_href\";s:21:\"http://www.17558.net/\";}','<a href=\"http://www.17558.net/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/MONTH/15_1340285502.gif\" width=\"708\" height=\"75\" /></a>','','','','','Y','0');");
E_D("replace into `modoer_adv_list` values('2','2','0','Modoer2.0发布','img','1289260800','0','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:38:\"/uploads/adv/2010-12/29_1292772237.jpg\";s:9:\"img_width\";s:3:\"958\";s:10:\"img_height\";s:2:\"90\";s:8:\"img_href\";s:22:\"http://www.modoer.com/\";}','<a href=\"http://www.modoer.com/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/2010-12/29_1292772237.jpg\" width=\"958\" height=\"90\" /></a>','','','','','Y','0');");

require("../../inc/footer.php");
?>