<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_review_opt`;");
E_C("CREATE TABLE `modoer_review_opt` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `flag` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_review_opt` values('1','1','sort1','��ζ','1','1');");
E_D("replace into `modoer_review_opt` values('2','1','sort2','����','2','1');");
E_D("replace into `modoer_review_opt` values('3','1','sort3','����','3','1');");
E_D("replace into `modoer_review_opt` values('4','1','sort4','�Լ۱�','4','1');");
E_D("replace into `modoer_review_opt` values('5','1','sort5','R5','5','0');");
E_D("replace into `modoer_review_opt` values('6','1','sort6','R6','6','0');");
E_D("replace into `modoer_review_opt` values('7','1','sort7','R7','7','0');");
E_D("replace into `modoer_review_opt` values('8','1','sort8','R8','8','0');");

require("../../inc/footer.php");
?>