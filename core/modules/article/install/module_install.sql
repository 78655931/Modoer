DROP TABLE IF EXISTS modoer_article_category;
CREATE TABLE modoer_article_category (
  catid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  pid smallint(5) NOT NULL DEFAULT '0',
  name varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  num mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_articles;
CREATE TABLE modoer_articles (
  articleid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  catid smallint(5) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) NOT NULL DEFAULT '0',
  dateline int(10) NOT NULL DEFAULT '0',
  att tinyint(1) NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  author varchar(20) NOT NULL DEFAULT '',
  subject varchar(60) NOT NULL DEFAULT '',
  keywords varchar(100) NOT NULL DEFAULT '',
  pageview mediumint(8) unsigned NOT NULL DEFAULT '0',
  digg mediumint(8) NOT NULL DEFAULT '0',
  closed_comment tinyint(1) unsigned NOT NULL DEFAULT '1',
  comments mediumint(8) unsigned NOT NULL DEFAULT '0',
  copyfrom varchar(200) NOT NULL DEFAULT '',
  introduce varchar(255) NOT NULL,
  status tinyint(1) NOT NULL DEFAULT '1',
  checker varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (articleid),
  KEY sid (sid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_article_data;
CREATE TABLE modoer_article_data (
  articleid mediumint(8) unsigned NOT NULL DEFAULT '0',
  content mediumtext NOT NULL,
  PRIMARY KEY  (articleid)
) TYPE=MyISAM;