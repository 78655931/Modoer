<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_product_model`;");
E_C("CREATE TABLE `modoer_product_model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `tablename` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `usearea` tinyint(1) NOT NULL DEFAULT '0',
  `item_name` varchar(200) NOT NULL DEFAULT '',
  `item_unit` varchar(200) NOT NULL DEFAULT '',
  `tplname_list` varchar(200) NOT NULL DEFAULT '',
  `tplname_detail` varchar(200) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>