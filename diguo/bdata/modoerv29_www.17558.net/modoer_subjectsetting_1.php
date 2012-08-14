<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectsetting`;");
E_C("CREATE TABLE `modoer_subjectsetting` (
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `variable` char(20) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  UNIQUE KEY `sid` (`sid`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>