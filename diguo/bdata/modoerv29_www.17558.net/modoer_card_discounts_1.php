<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_card_discounts`;");
E_C("CREATE TABLE `modoer_card_discounts` (
  `sid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cardsort` enum('both','largess','discount') NOT NULL DEFAULT 'discount',
  `discount` decimal(4,1) NOT NULL DEFAULT '0.0',
  `largess` varchar(100) NOT NULL DEFAULT '',
  `exception` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `finer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `available` (`available`),
  KEY `finer` (`finer`,`available`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>