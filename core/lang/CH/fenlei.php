<?php
return array(

    'fenlei_title_my' => '我的分类信息',
    'fenlei_title_owner' => '分类信息管理',

    'fenlei_status_0' => '未审核',
    'fenlei_status_1' => '已审核',
    'fenlei_status_2' => '未通过',

    'fenlei_days' => '天',
    'fenlei_tops' => array(1=>'全局置顶', 2=>'大类置顶', 3=>'子类置顶'),

    'fenlei_empty' => '对不起，您查看的分类信息不存在。',

    'fenlei_category_empty' => '对不起，您查看的分类不存在。',
    'fenlei_category_name_empty' => '对不起，您未填写分类名称',

    'fenlei_post_not_category' => '对不起，管理员未设置分类，无法发布信息。',
    'fenlei_post_sid_empty' => '对不起，您未选择关联主题，请返回设置。',
    'fenlei_post_catid_empty' => '对不起，未选择信息所属分类，请返回填写。',
    'fenlei_post_aid_empty' => '对不起，未选择信息地区，请返回填写。',
    'fenlei_post_catid_invalid' => '对不起，分类无效，请重新选择。',
    'fenlei_post_not_subcat' => '对不起，不能选择主分类，请返回选择子分类。',
    'fenlei_post_contact_invalid' => '对不起，您输入了无效的联系方式，请返回填写。',
    'fenlei_post_subject_empty' => '对不起，未填写信息标题，请返回填写。',
    'fenlei_post_linkmain_empty' => '对不起，未填写联系人，请返回填写。',
    'fenlei_post_content_empty' => '对不起，未填写信息内容，请返回填写。',
    'fenlei_post_pointtype_empty' => '对不起，系统未设置交易级分类信息，请联系管理员。',
    'fenlei_post_point_not_enough' => '对不起，您目前的积分不足以置顶和变色，需花费 %s %s，您目前只有 %s %s。',
    'fenlei_post_top_empty' => '对不起，您未选择置顶类型。',
    'fenlei_post_top_invalid' => '对不起，您未选择或置顶类型无效。',
    'fenlei_post_top_day_empty' => '对不起，您未选择置顶天数。',
    'fenlei_post_top_day_invalid' => '对不起，您未选择或变色时间无效。',
    'fenlei_post_color_empty' => '对不起，您未选择变色类型。',
    'fenlei_post_color_invalid' => '对不起，您未选择或变色类型无效。',
    'fenlei_post_color_day_empty' => '对不起，您未选择变色天数。',
    'fenlei_post_color_day_invalid' => '对不起，您未选择或变色时间无效。',
    'fenlei_post_system_error' => '对不起，系统后台设置有误，请联系管理员',

    'fenlei_access_disable' => '对不起，您没有权限发布分类信息。',
    'fenlei_access_delete' => '对不起，您没有权限删除分类信息',

    'fenlei_feed_icon' => 'thread',
    'fenlei_feed_title_template' => '{username} 发布了一条分类信息',
    'fenlei_feed_body_template' => '{subject}',

    'fenlei_feed_subject_title_template' => '{item_name} {subject} 发布了一条分类信息',
    'fenlei_feed_subject_body_template' => '{subject}',

    'fenlei_redirect_add' => array('发布新信息', url("fenlei/member/ac/manage/op/add/role/$_G[role]")),
    'fenlei_update_point_add_des' => '发布信息购买置顶和或变色',
);
?>