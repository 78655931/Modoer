<?php

$_G['loader']->helper('sql');

sql_alter_field('members', 'rmb', 'add', "rmb DECIMAL ( 9, 2 ) NOT NULL DEFAULT '0.0' AFTER coin");

$_G['db']->exec("ALTER TABLE {$_G['dns']['dbpre']}pay_log AUTO_INCREMENT = 1000000001");

?>