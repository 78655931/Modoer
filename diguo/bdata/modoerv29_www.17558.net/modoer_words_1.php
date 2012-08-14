<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_words`;");
E_C("CREATE TABLE `modoer_words` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `expression` varchar(255) NOT NULL DEFAULT '',
  `admin` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>