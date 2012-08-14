<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_modules`;");
E_C("CREATE TABLE `modoer_modules` (
  `moduleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL DEFAULT '',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `version` varchar(60) NOT NULL DEFAULT '',
  `releasetime` date NOT NULL DEFAULT '0000-00-00',
  `reliant` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `introduce` text NOT NULL,
  `siteurl` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `checkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`moduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_modules` values('1','member','1','8','��Ա','member','0','','1.1','2008-09-30','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('2','item','1','1','����','item','0','','2.5','2011-05-24','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('3','space','1','9','���˿ռ�','space','0','','1.1','2008-09-30','','Moufer Studio','','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('4','link','0','10','��������','link','0','','2.0','2010-05-04','','moufer','��������ģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php');");
E_D("replace into `modoer_modules` values('5','product','0','2','�����Ʒ','product','0','','1.1','2010-03-27','','moufer','��������������Ĳ�Ʒ�б�','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/product.php');");
E_D("replace into `modoer_modules` values('6','comment','0','6','��Ա����','comment','0','','1.0','2010-04-01','','moufer','����ģ�������������Ҫ�������۵�ģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php');");
E_D("replace into `modoer_modules` values('7','exchange','0','5','��Ʒ�һ�','exchange','0','','3.0','2012-05-01','','moufer,��','ʹ����վ��Ҷһ���Ʒ�����ѽ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/exchange.php');");
E_D("replace into `modoer_modules` values('8','article','0','3','������Ѷ','article','0','','2.0','2010-04-14','','moufer','������Ϣ��������վ��Ϣ��������Ѷ','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/article.php');");
E_D("replace into `modoer_modules` values('9','card','0','7','��Ա��','card','0','','2.0','2010-05-06','item','moufer','��Ա��ģ���û����������������ṩ�Ż��ۿ���Ϣ','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/card.php');");
E_D("replace into `modoer_modules` values('10','coupon','0','4','�Ż�ȯ','coupon','0','','2.0','2010-05-10','','moufer','�Ż�ȯģ�飬�ṩ����ʹ�ӡ�ۿۺ��Ż���Ϣ','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/coupon.php');");
E_D("replace into `modoer_modules` values('11','adv','0','10','���','adv','0','','2.0','2010-12-13','','moufer','�Զ�����ģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/adv.php');");
E_D("replace into `modoer_modules` values('12','review','1','2','����','review','0','','2.5','2011-05-24','','Moufer Studio','����ģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('13','sms','0','10','���ŷ���','sms','0','','1.0','2011-12-06','','moufer','���ŷ���ģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/sms.php');");
E_D("replace into `modoer_modules` values('14','pay','0','10','���߳�ֵ','pay','0','','2.2','2012-03-30','','moufer','���߻��ֳ�ֵģ��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/pay.php');");
E_D("replace into `modoer_modules` values('15','discussion','0','10','������','discussion','0','','1.0','2012-03-10','','moufer','���������ڵ����ۻ��⽻��','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/discuss.php');");

require("../../inc/footer.php");
?>