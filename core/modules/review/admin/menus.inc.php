<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '功能设置',
        'review|模块设置|config',
		'review|点评项管理|opt_group',
    ),
    array(
        'title' => '点评/回应',
        'review|审核点评|review|checklist',
        'review|审核回应|respond|checklist',
        'review|点评管理|review|list',
        'review|点评举报|report|list',
        'review|回应管理|respond|list',
    ),
);
?>