CREATE TABLE IF NOT EXISTS modoer_card_apply (
  applyid mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(20) NOT NULL default '',
  linkman varchar(20) NOT NULL default '',
  tel varchar(20) NOT NULL default '',
  mobile varchar(20) NOT NULL default '',
  address varchar(255) NOT NULL default '',
  postcode varchar(10) NOT NULL default '',
  num smallint(5) unsigned NOT NULL default '1',
  coin int(10) NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  status tinyint(1) unsigned NOT NULL default '1',
  comment text NOT NULL,
  checker varchar(30) NOT NULL default '',
  checktime int(10) NOT NULL default '0',
  checkmsg text NOT NULL,
  PRIMARY KEY  (applyid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_card_discounts;
CREATE TABLE modoer_card_discounts (
  sid mediumint(8) unsigned NOT NULL auto_increment,
  cardsort enum('both','largess','discount') NOT NULL default 'discount',
  discount decimal(4,1) NOT NULL default '0.0',
  largess varchar(100) NOT NULL default '',
  exception varchar(255) NOT NULL default '',
  addtime int(10) unsigned NOT NULL default '0',
  available tinyint(1) NOT NULL default '1',
  finer tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (sid),
  KEY available (available),
  KEY finer (finer,available)
) TYPE=MyISAM;