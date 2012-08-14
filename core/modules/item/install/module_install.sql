CREATE TABLE modoer_field_shop (
  itemid mediumint(8) unsigned NOT NULL,
  templateid smallint(5) NOT NULL,
  address varchar(100) NOT NULL,
  mobile varchar(50) NOT NULL,
  tel1 varchar(50) NOT NULL,
  tel2 varchar(50) NOT NULL,
  website varchar(255) NOT NULL,
  video varchar(255) NOT NULL,
  content mediumtext NOT NULL,
  PRIMARY KEY  (itemid)
) TYPE=MyISAM;

CREATE TABLE modoer_fields (
  fieldid mediumint(8) unsigned NOT NULL auto_increment,
  tablename varchar(25) NOT NULL,
  fieldname varchar(50) NOT NULL,
  size int(10) NOT NULL,
  title varchar(100) NOT NULL,
  note mediumtext NOT NULL,
  type varchar(20) NOT NULL,
  defaultvalue mediumtext NOT NULL,
  options mediumtext NOT NULL,
  formtype varchar(20) NOT NULL,
  inputtool varchar(20) NOT NULL,
  inputlimit varchar(20) NOT NULL default '0',
  listorder smallint(5) NOT NULL default '0',
  allownull tinyint(1) unsigned NOT NULL default '1',
  enablelist tinyint(1) unsigned NOT NULL default '0',
  enablehtml tinyint(1) unsigned NOT NULL default '0',
  enablesearch tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (fieldid),
  KEY tablename (tablename)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE modoer_item_logs (
  logid mediumint(8) unsigned NOT NULL auto_increment,
  itemid mediumint(8) unsigned NOT NULL,
  uid mediumint(8) unsigned NOT NULL,
  username varchar(20) NOT NULL,
  logtype tinyint(2) unsigned NOT NULL,
  dateline int(10) unsigned NOT NULL,
  invalid tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (logid)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE modoer_item_total (
  itemid mediumint(8) unsigned NOT NULL,
  sumsort mediumint(8) NOT NULL,
  sort1 mediumint(8) NOT NULL,
  sort2 mediumint(8) NOT NULL,
  sort3 mediumint(8) NOT NULL,
  sort4 mediumint(8) NOT NULL,
  sort5 mediumint(8) NOT NULL,
  sort6 mediumint(8) NOT NULL,
  sort7 mediumint(8) NOT NULL,
  sort8 mediumint(8) NOT NULL,
  enjoy mediumint(8) NOT NULL,
  price mediumint(8) unsigned NOT NULL,
  reviews smallint(5) unsigned NOT NULL,
  pictures smallint(5) unsigned NOT NULL,
  pageviews mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (itemid)
) TYPE=MyISAM;

CREATE TABLE modoer_items (
  itemid mediumint(8) unsigned NOT NULL auto_increment,
  classid smallint(5) NOT NULL,
  areaid smallint(5) NOT NULL,
  name varchar(80) NOT NULL,
  subname varchar(80) NOT NULL,
  tags varchar(40) NOT NULL,
  description varchar(255) NOT NULL,
  owneruid mediumint(8) unsigned NOT NULL,
  ownername varchar(20) NOT NULL,
  status tinyint(2) NOT NULL,
  addtime int(10) NOT NULL,
  level tinyint(3) NOT NULL,
  mappoint varchar(60) NOT NULL,
  listorder tinyint(3) NOT NULL,
  PRIMARY KEY  (itemid)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE modoer_models (
  modelid smallint(5) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL,
  tablename varchar(20) NOT NULL,
  description varchar(255) NOT NULL,
  tpl_list varchar(50) NOT NULL,
  tpl_detail varchar(50) NOT NULL,
  disable tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (modelid)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE modoer_myitems (
  uid mediumint(8) unsigned NOT NULL,
  itemid mediumint(8) unsigned NOT NULL,
  lasttime int(10) unsigned NOT NULL,
  PRIMARY KEY  (uid,itemid)
) TYPE=MyISAM;

