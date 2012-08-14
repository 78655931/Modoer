DROP TABLE IF EXISTS modoer_product_category;
CREATE TABLE modoer_product_category (
  catid mediumint(8) unsigned NOT NULL auto_increment,
  sid mediumint(8) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  listorder smallint(5) NOT NULL default '0',
  num mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (catid),
  KEY sid (sid,catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product;
CREATE TABLE modoer_product (
  pid mediumint(8) unsigned NOT NULL auto_increment,
  sid mediumint(8) unsigned NOT NULL default '0',
  catid mediumint(8) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(20) NOT NULL default '',
  subject varchar(60) NOT NULL default '',
  grade smallint(5) NOT NULL default '0',
  pageview mediumint(8) unsigned NOT NULL default '0',
  comments mediumint(8) NOT NULL default '0',
  thumb varchar(255) NOT NULL default '',
  introduce varchar(255) NOT NULL default '',
  closed_comment tinyint(1) NOT NULL default '0',
  status tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (pid),
  KEY catid (sid,catid)
) TYPE=MyISAM;