<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2010 风格店铺
* @website www.cmsky.org
*/
return array(
    'ask_answer_status_0' => '未审核',
    'ask_answer_status_1' => '已审核',

    'ask_title' => '问题',
    'ask_hook_comment_name' => '问题回答',

    'ask_title_g_ask' => '提问管理',
    'ask_title_m_ask' => '我的提问',

    'ask_access_disable' => '对不起，您没有权限发表提问。',
    'ask_access_delete' => '对不起，您没有权限删除提问。',
    'ask_access_editor' => '对不起，您没有权限编辑提问。',

	'ask_cfg_pointtype_empty' => '对不起，管理员未在后台问答模块设置问答关联积分。',

    'ask_category_empty' => '对不起，问答分类不存在或已删除，请返回。',
    'ask_category_move_dest_empty' => '对不起，您未选择转移对象，请返回选择。',
    'ask_category_move_equal' => '对不起，问答分类必须转移到其他大分类，请返回。',

    'ask_empty' => '对不起，问答信息不存在或已删除或未审核。',
	'ask_answer_empty' => '对不起，您查看的回答信息不存在或未审核。',
    'ask_post_subject_empty' => '对不起，您未填写问题标题，请返回填写。',
    'ask_post_subject_len' => '问题标题请控制在 %d - %d 个字符，请返回修改。',
    'ask_post_catid_empty' => '对不起，您未选择问题二级分类，请返回选择。',
    'ask_post_author_empty' => '对不起，您未填写问题作者，请返回填写。',
    'ask_post_content_empty' => '对不起，您未填写问题内容，请返回填写。',
    'ask_post_content_len' => '问题内容请控制在 %d - %d 个字符，请返回修改。',
    'ask_post_sid_empty' => '对不起，您未关联主题，请返回选择。',
    'ask_post_sid_member_disable' => '对不起，普通会员不允许关联主题，请返回修改。',

    'ask_redirect_add' => array('发表新问题', url('ask/member/ac/ask/op/add')),
    'ask_extra_succeed' => '提交成功！请多多关注您的问题！',
    'ask_myself' => '您无法关闭不属于自己的问题。',
    'ask_answer_succeed' => '您的回答提交成功！',
	'ask_answer_succeed_check' => '回答成功，请耐心等待管理员审核。',
    'ask_psup_succeed' => '采纳答案成功！',
    'ask_newanswer_succeed' => '修改回复成功！',

    'ask_rss_des' => '[modoer]%s RSS 聚合服务 - %s',

    'ask_feed_icon' => 'thread',
    'ask_feed_title_template' => '{username} 发表了一个问题',
    'ask_feed_body_template' => '{subject}',
    'ask_point_not_enough' => '对不起，您的积分不足 %s，无法进行提问，您可以通过增加点评，在线充值等行为来积累积分。',
    'ask_update_point_dec_des' => '发表 %d 个问题',
    'ask_update_point_add_des' => '发表 %d 个回复',
    'ask_update_point_spup_answer' => '回答问题被采纳未最佳回答',
);
?>