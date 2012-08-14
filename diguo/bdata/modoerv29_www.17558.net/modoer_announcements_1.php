<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_announcements`;");
E_C("CREATE TABLE `modoer_announcements` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `orders` smallint(5) NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `author` varchar(50) NOT NULL DEFAULT '',
  `pageview` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_announcements` values('1','0','好东西分享','0','<p><a href=\"http://www.17558.net\">www.17558.net</a></p>','搜虎精品社区','0','1340285608','1');");
E_D("replace into `modoer_announcements` values('2','0','最新电影下载','0','<p>kan.17558.net</p>','搜虎精品社区','0','1340285614','1');");
E_D("replace into `modoer_announcements` values('3','0','免备案主机','0','<p>host.17558.net</p>','搜虎精品社区','0','1340285625','1');");
E_D("replace into `modoer_announcements` values('4','0','公告测试www.17558.net','0','<a href=\"http://www.17558.net\">www.17558.net</a>','搜虎精品社区','0','1340285636','1');");

require("../../inc/footer.php");
?>