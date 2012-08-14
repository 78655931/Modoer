<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_category`;");
E_C("CREATE TABLE `modoer_category` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) NOT NULL DEFAULT '0',
  `review_opt_gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `subcats` varchar(255) NOT NULL,
  `nonuse_subcats` varchar(255) NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_category` values('1','0','1','1','1','1','美食','0','a:22:{s:9:\"gusetbook\";s:1:\"1\";s:13:\"subject_apply\";s:1:\"1\";s:19:\"subject_apply_uppic\";s:1:\"1\";s:24:\"subject_apply_uppic_name\";s:8:\"营业执照\";s:8:\"useprice\";s:1:\"1\";s:17:\"useprice_required\";s:1:\"1\";s:14:\"useprice_title\";s:8:\"人均消费\";s:13:\"useprice_unit\";s:5:\"元/人\";s:9:\"useeffect\";s:1:\"1\";s:7:\"effect1\";s:4:\"去过\";s:7:\"effect2\";s:4:\"想去\";s:8:\"taggroup\";a:1:{i:0;s:1:\"1\";}s:9:\"itemcheck\";s:1:\"0\";s:11:\"reviewcheck\";s:1:\"0\";s:12:\"picturecheck\";s:1:\"0\";s:14:\"guestbookcheck\";s:1:\"0\";s:12:\"guest_review\";s:1:\"0\";s:10:\"templateid\";s:1:\"0\";s:9:\"listorder\";s:7:\"addtime\";s:15:\"product_modelid\";s:1:\"0\";s:13:\"meta_keywords\";s:12:\"餐饮，美食，\";s:16:\"meta_description\";s:12:\"点评餐饮美食\";}','0','1','2,3','');");
E_D("replace into `modoer_category` values('2','1','2','1','0','2','自助餐','0','','0','1','','');");
E_D("replace into `modoer_category` values('3','1','2','1','0','3','海鲜','0','','0','1','','');");

require("../../inc/footer.php");
?>