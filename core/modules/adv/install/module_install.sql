DROP TABLE IF EXISTS modoer_adv_list;
CREATE TABLE modoer_adv_list (
  adid mediumint(8) unsigned NOT NULL auto_increment,
  apid smallint(5) unsigned NOT NULL default '0',
  city_id mediumint(8) unsigned NOT NULL default '0',
  adname varchar(60) NOT NULL default '',
  sort enum('word','flash','code','img') NOT NULL,
  begintime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  config text NOT NULL,
  code text NOT NULL,
  attr char(10) NOT NULL default '',
  ader varchar(255) NOT NULL default '',
  adtel varchar(255) NOT NULL default '',
  ademail varchar(255) NOT NULL default '',
  enabled char(1) NOT NULL default 'Y',
  listorder smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (adid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_adv_place;
CREATE TABLE modoer_adv_place (
  apid smallint(5) unsigned NOT NULL auto_increment,
  templateid smallint(5) unsigned NOT NULL default '0',
  name varchar(60) NOT NULL default '',
  des varchar(255) NOT NULL default '',
  template text NOT NULL,
  enabled char(1) NOT NULL default 'Y',
  PRIMARY KEY  (apid),
  UNIQUE KEY name (name)
) TYPE=MyISAM;