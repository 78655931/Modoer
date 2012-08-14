<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_article_category`;");
E_C("CREATE TABLE `modoer_article_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_article_category` values('1','0','默认分类','0','0');");
E_D("replace into `modoer_article_category` values('2','1','默认子分类','0','1');");

require("../../inc/footer.php");
?>