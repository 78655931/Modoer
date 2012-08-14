DROP TABLE IF EXISTS modoer_coupon_category;
CREATE TABLE modoer_coupon_category (
  catid smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(40) NOT NULL DEFAULT '',
  num mediumint(0) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY  (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_coupons;
CREATE TABLE modoer_coupons (
  couponid mediumint(8) unsigned NOT NULL auto_increment,
  catid smallint(5) unsigned NOT NULL default '0',
  sid mediumint(8) unsigned NOT NULL default '0',
  uid mediumint(8) NOT NULL default '0',
  username varchar(30) NOT NULL default '',
  thumb varchar(255) NOT NULL default '',
  picture varchar(255) NOT NULL default '',
  starttime int(10) NOT NULL default '0',
  endtime int(10) NOT NULL default '0',
  subject varchar(100) NOT NULL default '',
  des varchar(50) NOT NULL default '',
  content text NOT NULL,
  effect1 mediumint(8) unsigned NOT NULL default '0',
  flag tinyint(1) unsigned NOT NULL default '1',
  prints  tinyint(8) unsigned NOT NULL default '0',
  comments tinyint(8) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  pageview int(10) NOT NULL default '0',
  status tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (couponid),
  KEY sid (sid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_coupon_print;
CREATE TABLE modoer_coupon_print (
  id mediumint(8) unsigned NOT NULL,
  couponid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL,
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  point mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY couponid (couponid),
  KEY uid (uid)
) TYPE=MyISAM;