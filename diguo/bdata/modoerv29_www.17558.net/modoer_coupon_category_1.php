<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_coupon_category`;");
E_C("CREATE TABLE `modoer_coupon_category` (
  `catid` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `num` mediumint(9) NOT NULL DEFAULT '0',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_coupon_category` values('1','��ʳ','0','0');");
E_D("replace into `modoer_coupon_category` values('2','����','0','0');");
E_D("replace into `modoer_coupon_category` values('3','����','0','0');");
E_D("replace into `modoer_coupon_category` values('4','Ů��','0','0');");
E_D("replace into `modoer_coupon_category` values('5','��Ӱ','0','0');");

require("../../inc/footer.php");
?>