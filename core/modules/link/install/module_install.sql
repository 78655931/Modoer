DROP TABLE IF EXISTS modoer_mylinks;
CREATE TABLE modoer_mylinks (
  linkid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(40) NOT NULL DEFAULT '',
  link varchar(100) NOT NULL DEFAULT '',
  logo varchar(100) NOT NULL DEFAULT '',
  des varchar(255) NOT NULL DEFAULT '',
  displayorder int(8) NOT NULL DEFAULT '0',
  ischeck tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (linkid)
) TYPE=MyISAM;

INSERT INTO modoer_mylinks (linkid, title, link, logo, des, displayorder, ischeck) VALUES(1, 'Modoer点评系统', 'http://www.modoer.com', '', 'Modoer 是一款点评网站管理系统，采用 PHP+MYSQL 设计，开放全部源码', 0, 1);