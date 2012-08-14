<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '功能设置',
        'article|模块配置|config',
    ),
    array(
        'title' => '文章管理',
		'article|分类管理|category',
		'article|文章审核|article|checklist',
		'article|文章管理|article',
        'article|下载远程图片|article|down_image',
        'article|发布文章|article|add',
    ),
);
?>