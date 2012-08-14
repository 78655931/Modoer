<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
return array(

    'item_subject' => '主题',
    'item_product' => '产品',
    'item_review' => '点评',
    'item_guestbook' => '留言',

    'item_hook_review_name' => '主题',

	'item_redirect_subjectlist' => '返回主题列表页',
	'item_redirect_addsubject' => '添加新主题',
	'item_redirect_subjectloglist' => '返回主题补充列表',

    'item_access_alow_subject' => '对不起，您没有权限添加主题信息。',
    'item_access_allow_edit_subject' => '对不起，您没有编辑主题信息。',
    'item_access_alow_review' => '对不起，您没有权限发布点评信息。',
    'item_access_alow_picture' => '对不起，您没有权限上传主题图片。',
    'item_access_alow_viewdigest' => '对不起，您没有权限查看精华点评。',
    'item_access_alow_repeat' => '对不起，您没有权限重复点评。',
    'item_access_alow_create_album' => '对不起，您没有权限新建相册。',
    'item_access_subjects' => '对不起，您的主题添加数量已满，无法增加新的主题。',
    'item_access_pictures' => '对不起，您的图片上传数量已满，无法上传更多图片。',
    'item_access_reviews' => '对不起，您的点评数量已满，无法增加新的点评。',

    'item_title_category' => '主题分类',
    'item_title_g_subject' => '主题管理',
    'item_title_m_subject' => '我的主题',
    'item_title_g_picture' => '图片管理',
    'item_title_m_picture' => '我的图片',
    'item_title_g_guestbook' => '留言管理',
    'item_title_m_guestbook' => '我的留言',

    'item_manage_access' => '对不起，您不是主题管理员。',

	'item_album_hook_comment_name' => '相册',

    'item_list_orderlist' => array(    
        'finer' => '推荐度',
        'addtime' => '登记时间',
        'reviews' => '点评数量',
        'enjoy' => '喜欢程度',
        'pageviews' => '浏览量',
        'picture' => '图片数量',
        'avgsort' => '综合点评',
    ),

    'item_list_displytype' => array(
        'normal' => '图文',
        'pic' => '图片',
    ),

    'item_empty_default_pid' => '对不起，系统未设置默认主分类，请到后台=>主题管理模块=>模块设置中设置默认分类。',

    'item_cat_empty' => '对不起，主题分类不存在，请返回。',
    'item_cat_invalid' => '对不起，你选择的主题分类不是一个有效的分类，请返回。',
    'item_cat_disabled' => '对不起，您查看的分类已停用。',
    'item_field_empty' => '对不起，不存在字段信息，请返回。',
    'item_model_empty' => '对不起，不存在分类模型信息，请返回。',
    'item_cat_sub_empty' => '对不起，您未选择二级分类，请返回选择。',
	'item_check_subject_title' => '查查是否存在',

    'item_model_export_field_empty' => '对不起，您请求导出的模型没有任何字段。',
    'item_model_importfile_invalid' => '对不起，您请求导入的模型文件无效。',
    'item_taggroup_export_empty' => '对不起，您请求导出的标签组(ID:%d)不存在。',

    'item_field_category_empty' => '对不起，您未选择管理主题分类，请至少选择一个。',
    'item_field_keyword_empty' => '对不起，您未选择店主昵称字段或商品标题关键字，请至少选择其中一个。',
    'item_field_q_field_empty' => '对不起，您未选择商铺关键字的关联字段。',

    'item_fieldvalidator_empty_city_id' => '未选择所属城市，请返回设置。',
    'item_fieldvalidator_empty_field' => '未完成 %s 的设置，请返回设置。',
    'item_fieldvalidator_text_len_limit' => '%s 的字符数量不能%s %d 个，请返回修改。',
    'item_fieldvalidator_invalid_number' => '%s 不是一个有效的数字，请返回填写。',
    'item_fieldvalidator_unselect' => '%s 未选择，请返回选择。',
    'item_fieldvalidator_not_exists' => '%s 不存在，请返回选择。',
    'item_fieldvalidator_invalid_subcat'=> '%s 不是一个有效的子分类，请返回选择。',
    'item_fieldvalidator_no_select_item' => '%s 没有可供选择，请返回检查。',
    'item_fieldvalidator_invalid_item' => '%s 不是有效的参数，请返回检查。',
	'item_fieldvalidator_option_exists' => '%s 选择内容不存在，请返回选择。',
    'item_fieldvalidator_tag_len' => '标签 %s 的数量不能超过 %d 个，请返回修改。',
    'item_fieldvalidator_subject_len' => ' %s 的关联数量不能超过 %d 个，请返回修改。',
    'item_fielddetail_subject_more' => '更多',

    'item_fieldform_template_disable' => '不使用风格',
    'item_fieldform_status_0' => '未审核',
    'item_fieldform_status_1' => '已审核',
    'item_fieldform_video_parse' => '解析视频地址',
    'item_fieldform_video_parse' => '解析视频地址',
    'item_fieldform_subject_search' => '搜索',
    'item_fieldform_subject_add' => '&gt;&gt;添加',
    'item_fieldform_subject_del' => '&lt;&lt;删除',

	'item_post_tag_charlen' => '对不起，标签单词不能多于 %s 个字符，请返回修改。',
	'item_post_tag_num' => '对不起，标签单词数量不能多于 %s 个，请返回修改。',
    'item_post_selectmappoint' => '选择地图坐标点',
	'item_post_exists_item' => '对不起，您添加的 %s 已存在，请返回另外填写一个。',
    'item_post_invalid_mappoint' => '无效的坐标点。',
    'item_post_owner_invalid' => '对不起，您设置的管理员不存在。',
    'item_post_domain_invalid' => '对不起，您设置的二级域名不正确(格式错误或者系统预留)。',
    'item_post_domain_exists' => '对不起，您设置的二级域名已存在。',
    'item_post_city_id_empty' => '对不起，您未选择主题所属城市。',
    'item_post_aid_invalid' => '对不起，您选择的主题地区无效。',
    'item_post_aid_level_invalid' => '对不起，您选择的主题地区不是一个有效的区县或街道。',
    'item_post_owner_expirydate_invalid' => '对不起，您设置的管理员有效期格式不正确，请返回设置，如：2010-9-24',
    'item_post_owner_expirydate_less' => '对不起，您设置的管理员有效期不能小于今天('.date('Y-m-d', time()).')，请返回设置。',
    'item_post_subbranch_enabled' => '对不起，关联分类未开启增加分店的功能。',
	
	'item_taoke_add_succeed' => '数据添加成功!',
	'item_taoke_logo_title' => '商铺LOGO',

	'item_reviewed' => '对不起，您已经点评过了，无法再次点评。',
	'item_empty' => '对不起，主题信息不存在或已删除。',
    'item_random_empty' => '对不起，没有找到任何信息。',
    'item_play_video' => '播放视频',

    'item_subject_move_catid_empty' => '对不起，您未指定移动分类。',
    'item_subject_move_catid_isroot' => '对不起，您无法移动到主分类，请移动到二级分类。',

    'item_guestbook_empty' => '对不起，您查看的留言信息不存在。',
    'item_guestbook_self' => '对不起，您无法给自己所属主题留言。',
    'item_guestbook_empty_content' => '对不起，您未填写留言信息，请返回填写。',
    'item_guestbook_empty_reply' => '对不起，您未填写回复内容，请返回填写。',
    'item_guestbook_content_charlen' => '请将留言内容的字符数量控制在 %s - %s 之间。',

    'item_picture_title' => '图片中心',
	'item_picture_empty_title' => '对不起，您未填写图片标题，请返回填写。',
	'item_picture_title_charlen' => '对不起，图片标题不能大于 %s 个字符，请返回修改。',
    'item_picture_empty' => '对不起，您查看的主题没有图片记录。',
    'item_picture_status_invalid' => '对不起，主题未审核或已锁定，无法提交点评。',
    'item_picture_wtext' => '%s | by %s',
    'item_upload_multi_off' => '对不起，网站没开启批量上传图片的功能。',

    'item_favorite_submitted' => '您已经关注过了。',
    'item_favorite_succeed' => '关注成功！',

    'item_log_empty' => '对不起，补充信息不存在或已删除。',
    'item_log_empty_content' => '对不起，您未填写补充信息。',
    'item_log_succeed' => '提交成功！感谢您对我们的支持！',

    'item_apply_disable' => '对不起，管理员未开启认领功能。',
    'item_apply_empty' => '对不起，您查看的认领信息不存在。',
    'item_apply_status_invalid' => '对不起，主题未审核或已锁定，无法进行认领。',
    'item_apply_empty_applyname' => '对不起，您未填写认领人名称。',
    'item_apply_empty_contact' => '对不起，您未填写联系方式。',
    'item_apply_empty_content' => '对不起，您未填写申请说明。',
    'item_apply_succeed' => '认领申请完毕，请等待管理员审核。',
    'item_apply_wait' => '您已经提交了认领请求，请等待管理员处理。',
    'item_apply_owner' => '您已经是管理员，无需再次申请。',
    'item_apply_pm_subject_1' => '恭喜您，您的申请已通过',
    'item_apply_pm_subject_2' => '很遗憾，您的申请未通过',
    'item_apply_pm_message' => '管理员在 %s 处理了您关于成为 %s 管理员的申请。',
    'item_apply_pm_message_2' => '管理员的处理信息：<br />',

    'item_tag_charlen' => '标签“%s”过长(不能超过 %s 个字符)，请返回修改。',
    'item_tag_empty' => '标签数据不存在。',
    'item_tag_closed' => '标签已关闭。',
    'item_taggroup_empty' => '对不起，标签组不存在。',

    'item_search_keyword_empty' => '对不起，您未填写搜索关键字，请返回填写。',
    'item_search_keyword_len' => '对不起，搜索关键字不能小于2个字节，请返回填写。',
    'item_search_result_empty' => '对不起，没有搜索到任何信息。',

    'item_top_title' => '主题排行',
    'item_top_title_all' => '综合',

    'item_allpic_title' => '主题图片',

    'item_rss_title' => 'RSS 聚合服务',

    'item_subject_feed_icon' => 'debate',
    'item_subject_feed_title_template' => '{username} 添加了一{item_unit}{item_name}',
    'item_subject_feed_body_template' => '{title} ({review})',

    'item_favorite_feed_icon' => 'favorite',
    'item_favorite_feed_title_template' => '{username} 关注了一{item_unit}{item_name}',
    'item_favorite_feed_body_template' => '{title} ({review})',

    'item_picture_feed_icon' => 'album',
    'item_picture_feed_title_template' => '{username} 上传了{num}张图片',
    'item_picture_feed_body_template' => '',


    'item_impress_empty' => '对不起，您未填写印象标签。',
    'item_impress_post_exist' => '对不起，您已经添加过了。',

    'item_album_name' => '%s默认相册',
	'item_album_normal' => '默认相册',
    'item_album_search' => '搜索相册"%s"',
	'item_album_empty' => '对不起，您查看的相册不存在。',
	'item_album_name_empty' => '对不起，您没有填写相册名称。',
	'item_album_title' => '相册',

    'item_tops_title' => '主题排行榜',
    'item_map_title' => '主题地图',

    'item_att_empty' => '对不起，属性组不存在。',

    'item_task_subtect_title' => '主题类任务',
    'item_task_picture_title' => '图片类任务',
    'item_task_favorite_title' => '收藏类任务',

    'item_taobaoke_add_category_empty' => '对不起，您未选择添加目标分类。',
    'item_taobaoke_addlost' => '添加失败',
    'item_taobaoke_search_key_empty' => '对不起，您必须选择店铺分类或商铺关键字。',
    'item_taobaoke_import_catid_empty' => '未选择导入的主题分类。',
    'item_taobaoke_import_linkfiled_empty' => '不存在关联项。',
    'item_taobaoke_import_cannot_model' => '无法获取已选择的主分类所属模型。',
    'item_taobaoke_import_select_link_empty' => '未必须导入数据的字段，请返回选择导入数据标签。',
    'item_taobaoke_store_empty' => '不存在店铺数据',
    'item_taobaoke_store_ownerid_invalid' => '无效的店主ID.',
    'item_taobaoke_store_getifo_invalid' => '无法获取店铺详细信息.',
    'item_taobaoke_inport_cannot_catid' => '无法获取导入指定的主分类.',
    'item_taobaoke_inport_cannot_field' => '无法获取字段关联信息.',
    'item_taobaoke_store_exists' => '商铺的已存在.',

    'item_not_used_template' => '当前主题未使用风格，无法进行风格管理。',
    'item_style_no_purchase' => '您没有购买过该主题风格，无法使用。',
    'item_style_expired' => '对不起，您准备使用的风格已过期，请对当前模板进行续费。',
    'item_style_invalid' => '主题风格无法使用，请联系网站管理员。',

    'item_notice_new_guestbook' => '%s 给 %s 提交了一条留言，<a href="%s" target="_blank">查看</a>',
    'item_notice_reply_guestbook' => '%s 管理员回复了您的留言，<a href="%s" target="_blank">查看</a>',
    'item_notice_subjectapply_succeed' => '恭喜您！您申请成为 %s 的管理员的申请已通过。',
    'item_notice_subjectapply_lost' => '很遗憾！您申请成为 %s 的管理员的申请未能通过，原因：%s 。',
);
?>