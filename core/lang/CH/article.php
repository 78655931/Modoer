<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
return array(

    'article_title' => '产品',
    'article_hook_comment_name' => '文章评论',

    'article_title_g_article' => '文章管理',
    'article_title_m_article' => '我的文章',

	'article_redirect_articlelist' => '返回文章列表页',
	'article_redirect_addarticle' => '添加新文章',

    'article_access_disable' => '对不起，您没有权限发表文章。',
    'article_access_delete' => '对不起，您没有权限删除文章。',

    'article_category_empty' => '对不起，文章分类不存在或已删除，请返回。',
    'article_category_move_dest_empty' => '对不起，您为选择转移对象，请返回选择。',
    'article_category_move_equal' => '对不起，文章分类必须转移到其他大分类，请返回。',

    'article_empty' => '对不起，文章信息已删除或未审核。',
    'article_post_city_id_invalid' => '对不起，您选择的城市不是一个有效的值，请返回修改。',
    'article_post_subject_empty' => '对不起，您未填写文章标题，请返回填写。',
    'article_post_subject_len' => '文章标题请控制在 %d - %d 个字符，请返回修改。',
    'article_post_catid_empty' => '对不起，您未选择文章二级分类，请返回选择。',
    'article_post_author_empty' => '对不起，您未填写文章作者，请返回填写。',
    'article_post_introduce_empty' => '对不起，您未填写文章简介，请返回填写。',
    'article_post_introduce_len' => '文章简介请控制在 %d - %d 个字符，请返回修改。',
    'article_post_content_empty' => '对不起，您未填写文章内容，请返回填写。',
    'article_post_content_len' => '文章内容请控制在 %d - %d 个字符，请返回修改。',
    'article_post_sid_empty' => '对不起，您未关联主题，请返回选择。',
    'article_post_sid_member_disable' => '对不起，普通会员不允许关联主题，请返回修改。',
    'article_post_thumb_not_found' => '对不起，文章封面图片不存在。',
    'article_post_thumb_can_not_copy' => '对不起，文章封面图片移动失败。',

    'article_redirect_add' => array('发表新文章', url('article/member/ac/article/op/add')),

    'article_rss_des' => '[modoer]%s RSS 聚合服务 - %s',

    'article_feed_icon' => 'thread',
    'article_feed_title_template' => '{username} 发表了一篇文章',
    'article_feed_body_template' => '{subject}',

    'article_feed_subject_title_template' => '{item_name} {subject} 增加了一篇文章',
    'article_feed_subject_body_template' => '{subject}',

    'article_down_image_day_invalid' => '对不起，您未设置一个有效的时间范围。',
    'article_down_image_catid_invalid' => '对不起，您未选择一个有效的文章分类。',
    'article_down_image_catid_empty' => '对不起，没有可供选择的文章文类。',
    'article_down_image_next' => '已处理文章《%s》(需下载：<span class="font_1">%d</span> 张，成功：<span class="font_3">%d</span> 张，失败：<span class="font_4">%d</span> 张)，继续处理下一篇文章的图片(%d/%d)。',
    'article_down_image_succeed' => '远程图片下载完毕！',

    'article_task_post_title' => '文章类任务',
);
?>