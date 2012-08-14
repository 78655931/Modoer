<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_mytask`;");
E_C("CREATE TABLE `modoer_mytask` (
  `uid` mediumint(8) unsigned NOT NULL,
  `taskid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `progress` smallint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `applytime` int(10) unsigned NOT NULL DEFAULT '0',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`taskid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>