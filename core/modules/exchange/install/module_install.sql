DROP TABLE IF EXISTS modoer_exchange_category;
CREATE TABLE modoer_exchange_category (
  catid smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(40) NOT NULL DEFAULT '',
  num mediumint(0) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY  (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_gifts;
CREATE TABLE modoer_exchange_gifts (
  giftid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  catid smallint(5) unsigned NOT NULL default '0',
  sid smallint(5) unsigned NOT NULL default '0',
  city_id smallint(5) unsigned NOT NULL default '0',
  name varchar(200) NOT NULL DEFAULT '',
  sort tinyint(1) unsigned NOT NULL DEFAULT '1',
  pattern tinyint(1) unsigned NOT NULL DEFAULT '1',
  reviewed tinyint(1) unsigned NOT NULL DEFAULT '0',
  available tinyint(1) NOT NULL DEFAULT '0',
  starttime int(10) NOT NULL DEFAULT '0',
  endtime int(10) NOT NULL DEFAULT '0',
  randomcodelen tinyint(1) NOT NULL DEFAULT '0',
  randomcode varchar(50) NOT NULL DEFAULT '',
  displayorder tinyint(3) NOT NULL DEFAULT '0',
  description text NOT NULL,
  price int(10) unsigned NOT NULL DEFAULT '0',
  point int(10) unsigned NOT NULL DEFAULT '0',
  pointtype enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  pointtype2 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  point3 int(10) unsigned NOT NULL DEFAULT '0',
  pointtype3 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  point4 int(10) unsigned NOT NULL DEFAULT '0',
  pointtype4 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  num int(10) unsigned NOT NULL DEFAULT '0',
  timenum int(10) unsigned NOT NULL DEFAULT '0',
  pageview mediumint(8) unsigned NOT NULL DEFAULT '0',
  thumb varchar(255) NOT NULL DEFAULT '',
  picture varchar(255) NOT NULL DEFAULT '',
  salevolume int(11) unsigned NOT NULL DEFAULT '0',
  usergroup varchar(255) NOT NULL DEFAULT '',
  allowtime varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (giftid),
  KEY available (available)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_log;
CREATE TABLE modoer_exchange_log (
  exchangeid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(25) NOT NULL DEFAULT '',
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  giftname varchar(200) NOT NULL DEFAULT '',
  price int(10) unsigned NOT NULL DEFAULT '0',
  pointtype enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  number int(10) unsigned NOT NULL DEFAULT '1',
  pay_style tinyint(1) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  status_extra varchar(255) NOT NULL DEFAULT '',
  exchangetime int(10) NOT NULL DEFAULT '0',
  contact mediumtext NOT NULL,
  checker varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (exchangeid),
  KEY uid (uid),
  KEY giftid (giftid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_serial;
CREATE TABLE modoer_exchange_serial (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  exchangeid mediumint(8) NOT NULL default '0',
  uid mediumint(8) NOT NULL default '0',
  serial varchar(255) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  sendtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY giftid (giftid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_lottery;
CREATE TABLE modoer_exchange_lottery (
  lid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL default '0',
  lotterycode varchar(50) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (lid),
  KEY giftid (giftid)
) TYPE=MyISAM;