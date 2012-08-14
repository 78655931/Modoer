<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '功能设置',
        'product|模块设置|config',
        'product|产品模型|model_list',
    ),
    array(
        'title' => '产品管理',
		'product|产品审核|product_list|checklist',
		'product|产品列表|product_list',
    ),
);
?>