DROP TABLE IF EXISTS modoer_discussion_reply;
CREATE TABLE modoer_discussion_reply (
  rpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tpid mediumint(8) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '10',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  isownerpost tinyint(1) unsigned NOT NULL DEFAULT '0',
  pictures text NOT NULL,
  content text NOT NULL,
  PRIMARY KEY (rpid),
  KEY tpid (tpid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_discussion_topic;
CREATE TABLE modoer_discussion_topic (
  tpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  subject varchar(255) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  replies mediumint(8) unsigned NOT NULL DEFAULT '0',
  replytime int(10) unsigned NOT NULL DEFAULT '0',
  isownerpost tinyint(1) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  pictures text NOT NULL,
  content text NOT NULL,
  PRIMARY KEY (tpid),
  KEY sid (sid,replytime)
) TYPE=MyISAM;
