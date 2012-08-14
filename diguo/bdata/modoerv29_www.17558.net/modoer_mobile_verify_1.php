<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_mobile_verify`;");
E_C("CREATE TABLE `modoer_mobile_verify` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniq` char(16) NOT NULL DEFAULT '',
  `mobile` char(20) NOT NULL DEFAULT '',
  `serial` char(6) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniq` (`uniq`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>