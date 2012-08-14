DROP TABLE IF EXISTS modoer_pay;
CREATE TABLE modoer_pay (
  payid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  order_flag varchar(30) NOT NULL  DEFAULT '',
  orderid mediumint(8) unsigned NOT NULL  DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL  DEFAULT '0',
  order_name varchar(255) NOT NULL  DEFAULT '',
  payment_orderid varchar(60) NOT NULL DEFAULT '',
  payment_name varchar(60) NOT NULL  DEFAULT '',
  creation_time int(10) NOT NULL  DEFAULT '0',
  pay_time int(10) unsigned NOT NULL  DEFAULT '0',
  price decimal(9,2) NOT NULL  DEFAULT '0.00',
  pay_status tinyint(1) NOT NULL DEFAULT '0',
  my_status tinyint(1) NOT NULL  DEFAULT '0',
  notify_url varchar(255) NOT NULL  DEFAULT '',
  callback_url varchar(255) NOT NULL  DEFAULT '',
  royalty TINYTEXT NOT NULL,
  PRIMARY KEY (payid),
  KEY order_flag (order_flag,orderid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_pay_card;
CREATE TABLE modoer_pay_card (
  cardid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  number varchar(30) NOT NULL DEFAULT '',
  password varchar(30) NOT NULL DEFAULT '',
  cztype varchar(15) DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  endtime int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  price int(10) unsigned NOT NULL DEFAULT '0',
  usetime int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (cardid),
  UNIQUE KEY number (number),
  KEY status (status,endtime),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_pay_log;
CREATE TABLE modoer_pay_log (
  orderid int(10) unsigned NOT NULL AUTO_INCREMENT,
  port_orderid varchar(60) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  point int(10) unsigned NOT NULL DEFAULT '0',
  cztype varchar(15) DEFAULT '',
  dateline int(10) NOT NULL DEFAULT '0',
  exchangetime int(10) NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  retcode varchar(10) NOT NULL DEFAULT '',
  ip varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (orderid)
) TYPE=MyISAM;
