<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_adminsessions`;");
E_C("CREATE TABLE `modoer_adminsessions` (
  `adminid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ip` char(16) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `errorcount` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=gbk");
E_D("replace into `modoer_adminsessions` values('1','127.0.0.1','1344582359','-1');");

require("../../inc/footer.php");
?>