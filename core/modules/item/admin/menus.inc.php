<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$modmenus = array(
    array(
        'title' => '功能设置',
        'item|模块设置|config',
        'item|分类管理|category_list',
        'item|模型管理|model_list',
        'item|属性组管理|att_cat',
        'item|标签组管理|taggroup',
        'item|风格管理|template',
    ),
    array(
        'title' => '内容管理',
        'item|审核主题|subject_check',
        'item|审核图片|picture_check',
        'item|审核留言|guestbook_check',
        'item|添加主题|subject_add',
        'item|主题管理|subject_list',
        'item|主题补充|subject_log',
        'item|主题认领|subject_apply',
        'item|相册管理|album',
        'item|图片管理|picture_list',
        'item|印象管理|impress',
        'item|留言管理|guestbook_list',
        'item|标签管理|tag_list',
    ),
);
?>